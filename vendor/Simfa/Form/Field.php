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

namespace Simfa\Form;

use Simfa\Model\Model;

class Field
{
	public const TYPE_TEXT = 'text';
	public const TYPE_EMAIL = 'email';
	public const TYPE_PASSWORD = 'password';
	public const TYPE_HIDDEN = 'hidden';
	public const DISABLED = 'disabled="disabled"';
	public const REQUIRED = 'required="required"';
	private const AUTOCOMPLETE = 'autocomplete="off"';

	private Model 	$model;
	private string 	$attribute;
	private ?string $label;
	private string 	$type;
	private string 	$holder;
	private string 	$disabled;
	private string 	$required;
	private string 	$default;
	private string 	$class;
	private ?string $submit = null;
	private ?string $submitExtra;
	private string 	$autoComplete = '';
	private string 	$custom = '';
	private mixed 	$value = null;

	public function __construct(Model $model, string $attribute)
	{
		$this->model = $model;
		$this->attribute = $attribute;
		$this->label = $attribute;
		$this->type = self::TYPE_TEXT;
		$this->disabled = '';
		$this->required = '';
		$this->default = '';
		$this->class = '';
	}


    /** magic method to convert from object to string
     * check php docs for in depth explanation
     * @return string
     */
    public function __toString(): string
    {
		if (!is_null($this->label))
			return $this->printDefault();

		return $this->printWithoutLabel();
	}

	private function printDefault(): string
	{
		if (!$this->submit)
			return sprintf('
				<div class="row">
					<div class="col-25">
						<label for="%s">%s</label>
					</div>
					<div class="col-75">
						<input type="%s" class="%s" id="%s" name="%s" value="%s" placeholder="%s" %s %s %s>
						<div class="invalid-feedback">
							%s
						</div>
					</div>
				</div>
				', !($this->type == self::TYPE_HIDDEN) ? ucfirst($this->attribute) : ''
				, $this->type != self::TYPE_HIDDEN ? (!empty($this->label) ? $this->label : ucfirst($this->attribute)) : ''
				, $this->type
				, $this->class . ' ' . ($this->model->hasError($this->attribute) ? 'is-invalid' : '')
				, $this->attribute
				, $this->attribute
				, $this->value ?: $this->model->{'get' . ucfirst($this->attribute)}()
				, !empty($this->holder) ? $this->holder : $this->attribute
				, $this->custom
				, $this->disabled
				, $this->required
				, $this->model->getFirstError($this->attribute)
			);

		return $this->printSubmitWithLabel();
	}


	private function printSubmitWithLabel():string
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
				%s
			</div>
			', !($this->type == self::TYPE_HIDDEN) ? ucfirst($this->attribute) : ''
			, $this->type != self::TYPE_HIDDEN ? (!empty($this->label) ? $this->label : ucfirst($this->attribute)) : ''
			, $this->type
			, $this->class . ' ' . ($this->model->hasError($this->attribute) ? 'is-invalid' : '')
			, $this->attribute
			, $this->attribute
			, $this->value ?: $this->model->{'get' . ucfirst($this->attribute)}()
			, !empty($this->holder) ? $this->holder : $this->attribute
			, $this->disabled . ' ' . $this->autoComplete
			, $this->required
			, $this->model->getFirstError($this->attribute)
			, $this->printSubmit()
		);
	}


	private function printWithoutLabel(): string
	{
		if ($this->submit) {
			return sprintf('
			<div class="row">
				<div class="col-75">
					<input type="%s" class="form-control %s" id="%s" name="%s" value="%s" placeholder="%s" %s %s>
					<div class="invalid-feedback">
						%s
					</div>
				</div>
				<div class="col-25">%s</div>
			</div>
			', $this->type
				, $this->class . ' ' . ($this->model->hasError($this->attribute) ? 'is-invalid' : '')
				, $this->attribute
				, $this->attribute
				, $this->model->{'get' . ucfirst($this->attribute)}()
				, !empty($this->holder) ? $this->holder : $this->attribute
				, $this->disabled . ' ' . $this->autoComplete
				, $this->required
				, $this->model->getFirstError($this->attribute)
				, $this->printSubmit()
			);
		}

		return sprintf('
			<div class="row">
				<div class="col-sm">
					<input type="%s" class="%s" id="%s" name="%s" value="%s" placeholder="%s" %s %s>
					<div class="invalid-feedback">
						%s
					</div>
				</div>
			</div>
			', $this->type
			, $this->class . ' ' . ($this->model->hasError($this->attribute) ? 'is-invalid' : '')
			, $this->attribute
			, $this->attribute
			, $this->model->{'get' . ucfirst($this->attribute)}()
			, !empty($this->holder) ? $this->holder : $this->attribute
			, $this->disabled . ' ' . $this->autoComplete
			, $this->required
			, $this->model->getFirstError($this->attribute)
		);
	}


    /**
     * set the field type to password
     * @return $this
     */
	public function passwordField(): Field
    {
		$this->type = self::TYPE_PASSWORD;
		
		return $this;
	}

    /**
     * set the field type to email
     * @return $this
     */
	public function emailField(): Field
    {
		$this->type = self::TYPE_EMAIL;

		return $this;
	}

    /**
     * make the field disabled
     * @return $this
     */
	public function disabled(): Field
    {
		$this->disabled = self::DISABLED;

		return $this;
	}

    /**
     * make the field required
     * @return $this
     */
	public function required(): Field
    {
		$this->required = self::REQUIRED;

		return $this;
	}


    /**
     * give a default value to the field
     * @param string $default
     * @return $this
     */
	public function default(string $default): Field
    {
		if (!$this->model->{'get' . ucfirst($this->attribute)}())
			$this->model->{'set' . ucfirst($this->attribute)}($default);

		return $this;
	}
	
	/**
	 * @param string $string
	 * @return $this
	 */
	public function setHolder(string $string): Field
    {
        $this->holder = $string;

        return $this;
    }

	public function hiddenField(): Field
	{
		$this->type = self::TYPE_HIDDEN;

		return $this;
	}

	public function setLabel(string $string): Field
	{
		$this->label = $string;

		return $this;
	}

	public function setClass(string $class): Field
	{
		$this->class = $class;

		return $this;
	}

	public function noLabel(): Field
	{
		$this->label = null;

		return $this;
	}

	public function addSubmit(string $value = 'submit', string $extra = null): Field
	{
		$this->submit = $value;
		$this->submitExtra = $extra;

		return $this;
	}

	private function printSubmit(): string
	{
		if ($this->submit)
			return '<input type="submit" class="form-control" ' . $this->submitExtra . ' value="' . $this->submit . '">';

		return '';
	}

	/**
	 * @return $this
	 */
	public function noAutocomplete(): static
	{
		$this->autoComplete = self::AUTOCOMPLETE;

		return $this;
	}

	/**
	 * @param string $custom
	 * @return $this
	 */
	public function setCustom(string $custom): static
	{
		$this->custom = $custom;

		return $this;
	}

	/**
	 * @param $value mixed will override any other value (default and model value)
	 * @return $this
	 */
	public function value(mixed $value): static
	{
		$this->value = $value;

		return $this;
	}
}
