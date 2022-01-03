<?php
/**
 * LoginForm.php
 * @author magoumi <agoumihunter@gmail.com>
 * Date : 3/27/2021
 * Time : 18:43
 */

namespace Model;

use Simfa\Framework\Application;
use Simfa\Framework\Db\DbModel;


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
	
	public function login(string $ref)
	{
		$user = User::findOne(['username' => $this->username]);
		if (!$user) {
			$user = User::findOne(['email' => $this->username]);
			if (!$user) {
				$this->addError('username', 'There is no such user');
				return FALSE;
			}
		}
		if (!password_verify($this->password, $user->password)) {
			$this->addError('password', 'Password is wrong');
			return false;
		}
		
		return Application::$APP->login($user, $ref);
 	}

}
