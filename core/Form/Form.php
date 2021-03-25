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

    /**
     * @param string $action
     * @param string $method
     * @param string $class
     * @return Form
     */
    public static function begin(string $action = '', string $method = '', string $class = ''): Form
    {
		// if (!$action)
		// 	$action = PHP_SELF // todo google this and change it later
		echo sprintf('<form action="%s" method="%s" class="class %s">', $action, $method, $class);
		return New Form();
	}

    /**
     *
     */
    public static function end()
	{
		echo '</form>';
	}

    /**
     * @param Model $model
     * @param string $attribute
     * @param string $label
     * @param string $placeholder
     * @return Field
     */
    public function field(Model $model, string $attribute, string $label = '', string $placeholder = ''): Field
    {
		$this->field = New Field($model, $attribute, $label, $placeholder);
		return $this->field;
	}

    /**
     * @param string $value
     * @return string
     */
    public function submit(string $value): string
	{
		return '<div class="row"><input type="submit" value="' . $value . '"></div>';
	}
}