<?php
/**
 * Session.php
 * @author magoumi <agoumihunter@gmail.com>
 * Date : 3/27/2021
 * Time : 15:00
 */

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
	
}