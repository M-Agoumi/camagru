<?php


namespace Simfa\Framework;


use Model\User;
use Simfa\Model\UserToken;

class Cookie
{
	public function __construct()
	{
		/** set test cookie */
		setcookie('cookies_active', '1',['path' => '/', 'httponly' => false, 'secure' => false]);

		/** check if user is logged save him in cookies */
		$userToken = New UserToken();
		if (!Application::isGuest()) {
			if (!$this->get('user_tk')) {
				$userToken->getOneBy('user', Application::$APP->user->getId());
				$userToken->used = 0;

				if (!$userToken->getId()){
					/** not token is saved generate one */
					$userToken->token = $this->generateToken();
					$userToken->user = Application::$APP->user;

					$userToken->save();
				} else {
					$userToken->token = $this->generateToken();
					$userToken->update();
				}

				$this->set('user_tk', $userToken->token, time() + (31556926));
			}
		} else {
			$token = $_COOKIE['user_tk'] ?? NULL;
			if ($token) {
				$userToken = $userToken->findOne(['token' => $token, 'used' => 0], ['user' => User::class]);
				if ($userToken->user) {
					Application::$APP->session->set('user', $userToken->user);
					$userToken->used = 1;
					$userToken->update();
					Application::$APP->response->redirect();
				}
			}
		}
	}

	public function set(string $key, string $value, int $expires = null): bool
	{
		if ($expires === 0)
			$expires = time() + 3600;
		return (setcookie($key, $value, ['expires' => $expires, 'path' => '/', 'httponly' => TRUE, 'secure' => false]));
	}

	public function unsetCookie(string $key)
	{
		setcookie($key, '', ['expires' => time() - 3600, 'path' => '/', 'httponly' => TRUE]);
	}

	public function get(string $key)
	{
		return $_COOKIE[$key] ?? false;
	}

	private function destroyCookies()
	{
		if (isset($_SERVER['HTTP_COOKIE'])) {
			$cookies = explode(';', $_SERVER['HTTP_COOKIE']);
			foreach($cookies as $cookie) {
				$parts = explode('=', $cookie);
				$name = trim($parts[0]);
				if ($name != 'PHPSESSID') {
					setcookie($name, '', time()-1000);
					setcookie($name, '', time()-1000, '/');
				}
			}
		}
	}

	/**
	 * @return string
	 */
	private function generateToken(): string
	{
		$token = openssl_random_pseudo_bytes(64);
		//Convert the binary data into hexadecimal representation.

		return (bin2hex($token));
	}
}
