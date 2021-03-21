<?php
# **************************************************************************** #
#                                                                              #
#                                                         :::      ::::::::    #
#    Field.php                                          :+:      :+:    :+:    #
#                                                     +:+ +:+         +:+      #
#    By: magoumi <agoumi.mohamed@outlook.com>       +#+  +:+       +#+         #
#                                                 +#+#+#+#+#+   +#+            #
#    Created: 2021/03/21 14:31:09 by magoumi           #+#    #+#              #
#    Updated: 2021/03/21 14:31:09 by magoumi          ###   ########lyon.fr    #
#                                                                              #
# **************************************************************************** #

include_once Application::$ROOT_DIR . "/models/Model.php";

class Field
{
	public const TYPE_TEXT = 'text';
	public const TYPE_EMAIL = 'email';
	public const TYPE_PASSWORD = 'password';
	public const DISABLED = 'disabled="disabled"';
	public const REQUIRED = 'required="required"';
	
	public Model $model;
	public string $attribute;
	public string $label;
	public string $type;
	public string $holder;
	public string $disabled;
	public string $required;
	public string $default;

	public function __construct(Model $model, string $attribute, string $label, string $holder)
	{
		$this->model = $model;
		$this->attribute = $attribute;
		$this->label = $label;
		$this->type = self::TYPE_TEXT;
		$this->holder = $holder;
		$this->disabled = '';
		$this->required = '';
		$this->default = '';
	}


	public function __toString()
	{
		return sprintf('
		<div class="row">
			<div class="col-25">
				<label for="%s">%s</label>
			</div>
			<div class="col-75">
				<input type="%s" class="%s" id="%s" name="%s" value="%s" placeholder="%s" %s %s>
				<div class="invalid-feedback">
					%s
				</div>
			</div>
		</div>
		', $this->attribute
		, !empty($this->label) ? $this->label : ucfirst($this->attribute)
		, $this->type
		, $this->model->hasError($this->attribute) ? 'is-invalid' : ''
		, $this->attribute
		, $this->attribute
		, $this->model->{$this->attribute}
		, !empty($this->holder) ? $this->holder : $this->attribute
		, $this->disabled
		, $this->required
		, $this->model->getFirstError($this->attribute)
		);
	}

	public function passwordField()
	{
		$this->type = self::TYPE_PASSWORD;
		
		return $this;
	}

	public function emailField()
	{
		$this->type = self::TYPE_EMAIL;

		return $this;
	}

	public function disabled()
	{
		$this->disabled = self::DISABLED;

		return $this;
	}

	public function required()
	{
		$this->required = self::REQUIRED;

		return $this;
	}

	public function default(string $default)
	{
		if (!$this->model->{$this->attribute})
			$this->model->{$this->attribute} = $default;

		return $this;
	}
}