<?php
/**
 * Session.php
 * @author magoumi <agoumihunter@gmail.com>
 * Date : 3/27/2021
 * Time : 15:00
 */

namespace Simfa\Framework;

class Session
{
	/** string to make sure we don't override any other session variable */
	private string $salt;
	private static string $FLASH_KEY;
	
	public function __construct()
	{
		$this->salt = Application::getAppConfig('session')['salt'] ?? 'flash_message_salt';
		self::$FLASH_KEY = 'flash_messages_' . $this->salt;
		session_start();
		$flashMessages = $_SESSION[self::$FLASH_KEY] ?? [];
		foreach ($flashMessages as $key => &$flashMessage) {
			$flashMessage['remove'] = true;
		}
		$_SESSION[self::$FLASH_KEY] = $flashMessages;
		if (!isset($_SESSION['__CSRF']))
			$_SESSION['__CSRF'] = [];
	}
	
	/**
	 * @param string $key
	 * @param string $value
	 */
	public function set(string $key, string $value)
	{
		$_SESSION[$key] = $value;
	}
	
	/**
	 * @param string $key
	 * @return mixed
	 */
	public function get(string $key): mixed
	{
		return $_SESSION[$key] ?? NULL;
	}

	/**
	 * @param string $key to remove
	 * @return void
	 */
	public function remove(string $key): void
	{
		unset($_SESSION[$key]);
	}

	/**
	 * @param $key     string success/warning/danger
	 * @param $message string the content of the flash message
	 */
	public function setFlash(string $key, string $message)
	{
		$_SESSION[self::$FLASH_KEY][$key] = [
			'remove' => false,
			'value' => $message
		];
	}
	
	/**
	 * @param $key string
	 * @return string|null content of the message if exists or false
	 */
	public function getFlash(string $key): ?string
	{
		return $_SESSION[self::$FLASH_KEY][$key]['value'] ?? false;
	}

	/**
	 * generate CSRF token
	 */
	public function generateCsrf(): void
	{
		try {
			$_SESSION['__CSRF'][] = [0, bin2hex(random_bytes(16)), time()];
		} catch (\Exception $e) {
			Application::$APP->catcher->catch($e);
		}
	}

	/**
	 * @param $needle
	 * @param $haystack
	 * @return bool|int
	 */
	public function array_search2d($needle, $haystack): bool|int
	{
		for ($i = 0, $l = count($haystack); $i < $l; ++$i) {
			if (in_array($needle, $haystack[$i])) return $i;
		}

		return false;
	}

	/**
	 * @param string $token
	 * @return bool
	 */
	public function checkCsrf(string $token): bool
	{
		$index = $this->array_search2d($token, $_SESSION['__CSRF']);
		if ($index !== false) {
			unset($_SESSION['__CSRF'][$index]);
			$_SESSION['__CSRF'] = array_values($_SESSION['__CSRF']);
			return true;
		}

		return false;
	}

	/**
	 * @return false|string csrf token if stored in session
	 */
	public function getCsrf(): bool|string
	{
		$i = 0;
		while ($i < count($_SESSION['__CSRF'])) {
			if (!$_SESSION['__CSRF'][$i][0]) {
				$_SESSION['__CSRF'][$i][0] = 1;
				return $_SESSION['__CSRF'][$i][1];
			}
			$i++;
		}

		$this->generateCsrf();

		return $this->getCsrf();
	}
	
	/**
	 * iterate over marked messages to be removed
	 * and removing them
	 */
	public function __destruct()
	{
		$flashMessages = $_SESSION[self::$FLASH_KEY] ?? [];
		
		foreach ($flashMessages as $key => &$flashMessage) {
			if ($flashMessage['remove']) {
				unset($flashMessages[$key]);
			}
		}

		$csrf_tokens = &$_SESSION['__CSRF'];
		$csrf_tokens = $csrf_tokens ?? [];
		$time = time();
		$expireTime = Application::getAppConfig('session')['csrf'] ?? 60;
		foreach ($csrf_tokens as $key => $token) {
			if (($time - $token[2]) / 60 > $expireTime)
				unset($csrf_tokens[$key]);
		}
		$_SESSION['__CSRF'] = array_values($_SESSION['__CSRF']);

		$_SESSION[self::$FLASH_KEY] = $flashMessages;
	}

	/** unset session variable
	 * @param string $key
	 * @return void
	 */
	public function unset(string $key): void
	{
		unset($_SESSION[$key]);
	}

	/** get session token or generate it if it doesn't exist yet
	 * @return mixed|null
	 */
	public function getToken($token = 'admin'): mixed
	{
		if ($this->get($token . '_token'))
			return $this->get($token . '_token');

		$this->set($token . '_token', bin2hex(openssl_random_pseudo_bytes(16)));

		return $this->getToken();
	}

}
