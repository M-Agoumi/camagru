<?php
/* ************************************************************************************************ */
/*                                                                                                  */
/*                                                        :::   ::::::::   ::::::::  :::::::::::    */
/*   User.php                                          :+:+:  :+:    :+: :+:    :+: :+:     :+:     */
/*                                                      +:+         +:+        +:+        +:+       */
/*   By: magoumi <magoumi@student.1337.ma>             +#+      +#++:      +#++:        +#+         */
/*                                                    +#+         +#+        +#+      +#+           */
/*   Created: 2021/03/18 08:59:29 by magoumi         #+#  #+#    #+# #+#    #+#     #+#             */
/*   Updated: 2021/03/18 08:59:29 by magoumi      ####### ########   ########      ###.ma           */
/*                                                                                                  */
/* ************************************************************************************************ */

namespace Model;
/**
 * Class User
 */

use Simfa\Framework\Db\DbModel;

/**
 * @method getPicture()
 * @method getUsername()
 * @method getPassword()
 * @method setPicture(mixed $filename)
 * @method setPass(bool $status)
 * @method setPassword(string $string)
 * @method setUsername(string $string)
 * @method getStatus()
 * @method setName(mixed $name)
 */
class User extends DbModel
{
	const STATUS_INACTIVE = 0;
	const STATUS_ACTIVE = 1;
	const STATUS_DELETED = 2;

	public ?int $entityID = null;
	public ?string $name = null;
	public ?string $username = null;
	public ?string $password = null;
	public ?int $status = self::STATUS_INACTIVE;
	public ?string $picture = null;
	public ?string $ip_address = null;
	public bool $pass = false;

	/**
	 * properties that aren't database table attributes
	 */
	protected static array $nonAttributes = ['pass'];

	/**
	 * @var array|string[] protected attributes from public sharing (Ex:API...)
	 */
	protected static array $protected = ['EntityID', 'password'];

	/** hash password before saving
	 * @return bool
	 */
	public function save(): bool
	{
		$this->status = self::STATUS_INACTIVE;
		$this->password = password_hash($this->password, PASSWORD_BCRYPT);

		return parent::save();
	}

	/** hash password before updating
	 * @return bool
	 */
	public function update(): bool
	{
		if ($this->pass)
			$this->password = password_hash($this->password, PASSWORD_BCRYPT);

		return parent::update();
	}

	/**
	 * @param string $email
	 * @return void
	 */
	public function setEmail(string $email): void
	{
		$mailObj = new Email();
		$mailObj->getOneBy('email', $email);
		if (!$mailObj->getId()) {
			$mailObj->setEmail($email);
			$mailObj->setUser($this);
			$mailObj->save();
		} else {
			$mailObj->setUser($this);
			$mailObj->update();
		}
	}

	/**
	 * @param null $id
	 * @return string|null
	 */
	public function getEmail($id = null): ?string
	{
		if (!$id)
			$id = $this->getId();
		$email = new Email();
		$query = $email->queryBuilder();
		$result = $query->select('email')->where('prime', 'like', '1')->and()
			->where('user', '=', $id)->get();

		if (count($result))
			return $result[0]['email'];

		return '';
	}

	/**
	 * @return array[]
	 */
	public function rules(): array
	{
		return [
			'name' => [self::RULE_REQUIRED],
			'username' => [self::RULE_REQUIRED, [
				self::RULE_UNIQUE, 'class' => self::class
			]],
			'password' => [
				self::RULE_NOT_ALL_NUMBER,
				self::RULE_REQUIRED,
				self::RULE_ONE_UPPERCASE,
				self::RULE_ONE_LOWERCASE,
				[self::RULE_MIN, 'min' => 8],
				[self::RULE_MAX, 'max' => 32]
			]
		];
	}
}
