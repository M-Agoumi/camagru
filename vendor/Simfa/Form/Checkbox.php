<?php

namespace Simfa\Form;

use Simfa\Model\Model;

class Checkbox
{
	/**
	 * @var Model
	 */
	private Model $model;

	/**
	 * @var string|null
	 */
	private ?string $label = null;

	/**
	 * @var string
	 */
	private string $attribute;

	/**
	 * @var string
	 */
	private string $class;


	public function __construct(Model $model, string $attribute)
	{
		$this->model = $model;
		$this->attribute = $attribute;
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

	private function printDefault()
	{
		return sprintf('
		<div class="row">
			<div class="col-25">
				<label for="%s">%s</label>
			</div>
			<div class="col-75">
				<p class="onoff"><input type="checkbox" value="1" id="checkboxID"><label for="checkboxID"></label></p>
				<div class="invalid-feedback">
					%s
				</div>
			</div>
		</div>
		', $this->attribute
			, !empty($this->label) ? $this->label : ucfirst($this->attribute)

		);
	}

	private function printWithoutLabel()
	{
		return '';
	}

	public function setLabel(string $string): Checkbox
	{
		$this->label = $string;

		return $this;
	}

	public function setClass(string $class): Checkbox
	{
		$this->class = $class;

		return $this;
	}

	public function noLabel(): Checkbox
	{
		$this->label = null;

		return $this;
	}
}
