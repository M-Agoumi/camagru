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
	private const SALT = 'ankhs';
	private const FLASH_KEY = 'flash_messages_' . self::SALT;
	
	public function __construct()
	{
		session_start();
		$flashMessages = $_SESSION[self::FLASH_KEY] ?? [];
		foreach ($flashMessages as $key => &$flashMessage) {
			$flashMessage['remove'] = true;
		}
		$_SESSION[self::FLASH_KEY] = $flashMessages;
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
	public function get(string $key)
	{
		return $_SESSION[$key] ?? NULL;
	}

	/**
	 * @param string $key to remove
	 */
	public function remove(string $key)
	{
		unset($_SESSION[$key]);
	}

	/**
	 * @param $key     string success/warning/danger
	 * @param $message string the content of the flash message
	 */
	public function setFlash(string $key, string $message)
	{
		$_SESSION[self::FLASH_KEY][$key] = [
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
		return $_SESSION[self::FLASH_KEY][$key]['value'] ?? false;
	}

	/**
	 * generate CSRF token
	 * @throws \Exception
	 */
	public function generateCsrf()
	{
		array_push($_SESSION['__CSRF'], [0, bin2hex(random_bytes(16))]);
	}

	public function array_search2d($needle, $haystack) {
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
	public function getCsrf()
	{
		$i = 0;
		while ($i < count($_SESSION['__CSRF'])) {
			if (!$_SESSION['__CSRF'][$i][0]) {
				$_SESSION['__CSRF'][$i][0] = 1;
				return $_SESSION['__CSRF'][$i][1];
			}
			$i++;
		}

		return false;
	}
	
	/**
	 * iterate over marked messages to be removed
	 * and removing them
	 */
	public function __destruct()
	{
		$flashMessages = $_SESSION[self::FLASH_KEY] ?? [];
		
		foreach ($flashMessages as $key => &$flashMessage) {
			if ($flashMessage['remove']) {
				unset($flashMessages[$key]);
			}
		}
		
		$_SESSION[self::FLASH_KEY] = $flashMessages;
	}

	public function unset(string $key)
	{
		unset($_SESSION[$key]);
	}

}
