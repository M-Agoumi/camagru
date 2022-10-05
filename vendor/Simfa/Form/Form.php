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

namespace Simfa\Form;

use Simfa\Framework\Application;
use Simfa\Model\Model;

class Form
{
	private static Model $model;

	/** echo the start of a form
	 * @param Model $model
	 * @param string $action
	 * @param string $method
	 * @param string $class
	 * @param string|null $event
	 * @param string $id
	 * @return Form instance so we can access its method to generate a form
	 */
	public static function begin(Model $model, string $action = '', string $method = 'POST', string $class = '', string $event = null, string $id = ''): Form
	{
		self::$model = $model;
		if (!$action)
			$action = Application::$APP->request->getPath();
		echo sprintf('<form action="%s" method="%s" class="form %s" %s>', $action, $method, $class, $event);
		echo sprintf('<input type="hidden" id="%s" name="__csrf" value="%s">', $id, Application::$APP->session->getCsrf());
		return new Form();
	}

	/**
	 * echo the close tag of the form
	 */
	public static function end()
	{
		echo '</form>';
	}

	/**
	 * generate a new form field
	 * @param Model $model
	 * @param string $attribute
	 * @param string $label
	 * @return Field
	 */
	public function field(string $attribute): Field
	{
		return new Field(self::$model, $attribute);
	}

	public function text(string $attribute): TextArea
	{
		return new TextArea(self::$model, $attribute);
	}

	public function select(string $attribute, $elements, $show = null): Select
	{
		return new Select(self::$model, $attribute, $elements, $show);
	}

	/** return submit type input
	 * @param string $value
	 * @param string $extra
	 * @return string
	 */
	public function submit(string $value = 'submit', string $extra = ''): string
	{
		return '<div class="row"><input type="submit" ' . $extra . ' value="' . $value . '"></div>';
	}

    public function checkbox(Model $model, string $attribute): Checkbox
	{
		return new Checkbox($model, $attribute);
    }

}
