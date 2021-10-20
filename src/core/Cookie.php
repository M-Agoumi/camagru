<?php


namespace core;


use models\core\UserToken;
use models\User;

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
				if (!$userToken->id){
					/** not token is saved generate one */
					$userToken = $this->generateToken();
					$userToken->save();
				} elseif ($userToken->used) {
					$userToken = $this->generateToken($userToken->id);
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
	 * @param int|null $id
	 * @return UserToken
	 */
	private function generateToken(int $id = null): UserToken
	{
		$userToken = New UserToken();

		$userToken->id = $id;
		$userToken->user = Application::$APP->user;

		$token = openssl_random_pseudo_bytes(64);
		//Convert the binary data into hexadecimal representation.
		$userToken->token = bin2hex($token);
		$userToken->used = 0;

		return $userToken;
	}
}
