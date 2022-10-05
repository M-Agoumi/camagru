<?php
/**
 * LoginForm.php
 * @author magoumi <agoumihunter@gmail.com>
 * Date : 3/27/2021
 * Time : 18:43
 */

namespace Model;

use Service\Mailer;
use Simfa\Framework\Application;
use Simfa\Framework\Db\DbModel;


/**
 * @method getPassword()
 */
class LoginForm extends DbModel
{
	protected string $id = '';
	protected string $username = '';
	protected string $password = '';
	
	/**
	 * the rules should be respect by each child model
	 * @return array
	 */
	public function rules(): array
	{
		return [
			'username' => [self::RULE_REQUIRED],
			'password' => [self::RULE_REQUIRED]
		];
	}

	/**
	 * @param string $ref
	 * @return bool|null
	 */
	public function login(string $ref): ?bool
	{
		$user = User::findOne(['username' => $this->getUsername()]);
		if (!$user->getId()) {
			$user = User::findOne(['email' => $this->getUsername()]);
			if (!$user->getId()) {
				$this->addError('username', 'There is no such user');
				return FALSE;
			}
		}
		if (!password_verify($this->getPassword(), $user->getPassword())) {
			$this->addError('password', 'Password is wrong');
			return false;
		}

		$mailer = new Mailer();
		$mailer->mail($user->getEmail(), 'New Login to your account', ['login-alert', [
			'time' => (new \DateTime('now'))->format('d-m-y H:i'),
			'name' => $user->getName(),
			'link' => Application::getEnvValue('URL') . route('auth.restore')
		]]);

		return Application::$APP->login($user, $ref);
 	}

}
