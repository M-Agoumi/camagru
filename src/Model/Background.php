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

namespace   Model;
/**
 * Class User
 */

use JetBrains\PhpStorm\ArrayShape;
use Simfa\Framework\Db\DbModel;

/**
 * @method getUser(): Model\User
 * @method setUser(DbModel $user)
 * @method setType(int $type)
 * @method getType(): int
 * @method setImage(string $image)
 * @method getImage()
 */
class Background extends DbModel
{
    protected ?int $entityID = null;
	protected ?User $user = null;
	protected ?int $type = 0;
	protected ?string $image = null;

	/**
	 * @var string
	 */
	protected static string $tableName = 'user_background';

	/**
	 * @var array|string[] protected attributes from public sharing (Ex:API...)
	 */
	protected static array $protected = ['EntityID'];

	public function save(): bool
	{
		$bg = new Background();
		$bg->getOneBy('user', $this->user->getId());
//		var_dump($bg);
//		die();
		if ($bg->getId()) {
			$bg->setType($this->type);
			$bg->setImage($this->image);
			return $bg->update();
		}
		return parent::save();
	}

	/**
	 * @return string[]
	 */
	#[ArrayShape(['user' => "string"])]
	protected function relationShips(): array
	{
		return ['user' => User::class];
	}
	/**
     * @return array[]
     */
    public function rules(): array
    {
        return [];
    }
}
