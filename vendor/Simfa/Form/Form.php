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
	public Field $field;
	public TextArea $textArea;
	public Select $select;

	/** echo the start of a form
	 * @param string $action
	 * @param string $method
	 * @param string $class
	 * @return Form instance so we can access it's method to generate a form
	 */
	public static function begin(string $action = '', string $method = 'POST', string $class = '', string $event = null): Form
	{
		if (!$action)
			$action = Application::$APP->request->getPath();
		Application::$APP->session->generateCsrf();
		echo sprintf('<form action="%s" method="%s" class="form %s" %s>', $action, $method, $class, $event);
		echo sprintf('<input type="hidden" name="__csrf" value="%s">', Application::$APP->session->getCsrf());
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
	public function field(Model $model, string $attribute): Field
	{
		$this->field = new Field($model, $attribute);
		return $this->field;
	}

	public function text(Model $model, string $attribute): TextArea
	{
		$this->textArea = new TextArea($model, $attribute);

		return $this->textArea;
	}

	public function select(Model $model, string $attribute, $elements): Select
	{
		$this->select = new Select($model, $attribute, $elements);

		return $this->select;
	}

	/** return a submit type input
	 * @param string $value
	 * @return string
	 */
	public function submit(string $value = 'submit', string $extra = ''): string
	{
		return '<div class="row"><input type="submit" ' . $extra . ' value="' . $value . '"></div>';
	}

}
