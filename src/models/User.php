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

namespace   models;
/**
 * Class User
 */

use core\Db\DbModel;

class User extends DbModel
{
	const STATUS_INACTIVE = 0;
	const STATUS_ACTIVE = 1;
	const STATUS_DELETED = 2;
	
    public ?int $id = null;
	public ?string $name = null;
	public ?string $username = null;
	public ?string $email = null;
	public ?string $password = null;
	public ?int $status = self::STATUS_INACTIVE;
	public ?string $picture = null;
	public ?string $ip_address = null;
	public bool $pass = false;

	/**
	 * @var string table name in the database
	 */
	protected static string $tableName = 'users';

	public function attributes(): array
	{
		return ['name', 'username', 'email', 'password', 'status', 'ip_address', 'picture'];
	}
	
	/**
	 * @return string
	 */
	public function primaryKey(): string
	{
		return 'id';
	}

	/**
	 * @return int|null
	 */
	public function getId(): ?int
	{
		return $this->id;
	}

	/**
	 * @return string|null
	 */
	public function getUsername(): ?string
	{
		return $this->username;
	}

	/**
	 * @return string|null
	 */
	public function getEmail(): ?string
	{
		return $this->email;
	}

    /**
     * @param string $email
     */
    public function setEmail(string $email)
    {
        $this->email = $email;
    }

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
     * @return array[]
     */
    public function rules(): array
    {
        return [
            'name' => [self::RULE_REQUIRED],
            'username' => [self::RULE_REQUIRED, [
            	self::RULE_UNIQUE, 'class' => self::class
            ]],
            'email' => [[self::RULE_UNIQUE, 'class' => self::class], self::RULE_REQUIRED, self::RULE_EMAIL],
            'password' => [self::RULE_REQUIRED, [self::RULE_MIN, 'min' => 8], [self::RULE_MAX, 'max' => 32]]
        ];
    }
}
