<?php
/**
 * Session.php
 * @author magoumi <agoumihunter@gmail.com>
 * Date : 3/27/2021
 * Time : 15:00
 */

namespace core;

class Session
{
	/** string to make sure we dont override any other session variable */
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
		if (!isset($_SESSION['__CSRF']) || (isset($_SESSION['__CSRF']) && $_SESSION['__CSRF'][0])) {
			$_SESSION['__CSRF'] = [0, bin2hex(random_bytes(16))];
//			echo "<script>console.log('created token: ". $_SESSION['__CSRF'][1] . "');</script>" ;
		}
	}

	/**
	 * @return false|string csrf token if stored in session
	 */
	public function getCsrf()
	{
		return $_SESSION['__CSRF'][1] ?? FALSE;
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
		if (isset($_SESSION['__CSRF']) && $_SESSION['__CSRF'][0]) {
			unset($_SESSION['__CSRF']);
		}
		if (isset($_SESSION['__CSRF']) && !$_SESSION['__CSRF'][0]) {
			$_SESSION['__CSRF'][0] = 1;
		}
	}
	
}