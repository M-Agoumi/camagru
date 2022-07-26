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

use Simfa\Framework\Db\DbModel;

/**
 * Class Email for User emails
 * @method getEmail()
 * @method void setUser(User $user)
 * @method void setEmail(string $string)
 * @method void setActive(int $int)
 * @method void setToken(string $string)
 * @method string getToken()
 * @method int getUsed()
 * @method void setUsed(int $int)
 * @method User|null getUser()
 * @method void setPrime(int $int)
 * @method int getPrime()
 * @method setConfirmed(int $int)
 * @method getConfirmed()
 */
class Email extends DbModel
{
	/**
	 * @var int|null
	 */
	protected ?int 		$entityID 	= null;
	protected ?string 	$email 		= null;
	protected ?User 	$user 		= null;
	protected string 	$token 		= '';
	protected int 		$used 		= 0;
	protected int 		$confirmed 	= 0;
	protected int 		$active		= 0;
	protected int 		$prime		= 1;

	/**
	 * @return array[]
	 */
	public function rules(): array
	{
		return ['email' => [self::RULE_REQUIRED, self::RULE_EMAIL]];
	}

	public function relationships(): array
	{
		return ['user' => User::class];
	}
}
