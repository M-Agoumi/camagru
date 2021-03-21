<?php
# **************************************************************************** #
#                                                                              #
#                                                         :::      ::::::::    #
#    Form.php                                           :+:      :+:    :+:    #
#                                                     +:+ +:+         +:+      #
#    By: magoumi <agoumi.mohamed@outlook.com>       +#+  +:+       +#+         #
#                                                 +#+#+#+#+#+   +#+            #
#    Created: 2021/03/21 14:06:02 by magoumi           #+#    #+#              #
#    Updated: 2021/03/21 14:06:02 by magoumi          ###   ########lyon.fr    #
#                                                                              #
# **************************************************************************** #

include_once "Field.php";

class Form
{
	public Field $field;

	// todo implements required and disabled methods
	// todo $form->field('username')->required()->disabled();
	public static function begin(string $action = '', string $method = '', string $class = '')
	{
		// if (!$action)
		// 	$action = PHP_SELF // todo google this and change it later
		echo sprintf('<form action="%s" method="%s" class="class %s">', $action, $method, $class);
		return New Form();
	}

	public static function end()
	{
		echo '</form>';
	}

	public function field(Model $model, string $attribute, string $label = '', string $placeholder = '')
	{
		$this->field = New Field($model, $attribute, $label, $placeholder);
		return $this->field;
	}

	public function submite(string $value): string
	{
		return '<div class="row"><input type="submit" value="' . $value . '"></div>';
	}
}