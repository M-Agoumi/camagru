<?php
/* ************************************************************************************************ */
/*                                                                                                  */
/*                                                        :::   ::::::::   ::::::::  :::::::::::    */
/*   Emote.php                                         :+:+:  :+:    :+: :+:    :+: :+:     :+:     */
/*                                                      +:+         +:+        +:+        +:+       */
/*   By: magoumi <magoumi@student.1337.ma>             +#+      +#++:      +#++:        +#+         */
/*                                                    +#+         +#+        +#+      +#+           */
/*   Created: 2022/02/16 17:11:51 by magoumi         #+#  #+#    #+# #+#    #+#     #+#             */
/*   Updated: 2022/02/16 17:11:51 by magoumi      ####### ########   ########      ###.ma           */
/*                                                                                                  */
/* ************************************************************************************************ */

namespace   Model;

/**
 * Class Emote
 */

use Simfa\Framework\Db\DbModel;

/**
 * @method setUser(string $name):void
 * @method getUser():string
 * @method setContent(string $fileName):void
 * @method getContent():string
 */
class Template extends DbModel
{
	protected ?int $entityID = null;
	protected ?User $user = NULL;
	protected ?string $content = NULL;

	/**
	 * @return array[]
	 */
	public function rules(): array
	{
		return [];
	}
}
