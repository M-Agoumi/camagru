<?php


namespace Simfa\Form;

use Simfa\Model\Model;

class TextArea
{

	public const DISABLED = 'disabled="disabled"';
	public const REQUIRED = 'required="required"';

	public Model $model;
	public string $attribute;
	public string $label;
	public string $holder;
	public string $disabled;
	public string $required;
	public string $default;
	private string $class;

	/**
	 * TextArea constructor.
	 * @param Model $model
	 * @param string $attribute
	 */
	public function __construct(Model $model, string $attribute)
	{
		$this->model = $model;
		$this->attribute = $attribute;
		$this->label = '';
		$this->disabled = '';
		$this->required = '';
		$this->default = '';
		$this->holder = '';
		$this->class = '';
	}

	/** magic method to convert from object to string
	 * check php docs for in depth explanation
	 * @return string
	 * <input type="%s" class="%s" id="%s" name="%s" value="%s" placeholder="%s" %s %s>
	 */
	public function __toString(): string
	{
		return sprintf('
		<div class="row">
			<div class="col-25">
				<label for="%s">%s</label>
			</div>
			<div class="col-75">
				<textarea class="%s" id="%s" name="%s" placeholder="%s" %s %s>%s</textarea>
				<div class="invalid-feedback">
					%s
				</div>
			</div>
		</div>
		', $this->attribute
			, !empty($this->label) ? $this->label : ucfirst($this->attribute)
			, $this->class . ' ' . ($this->model->hasError($this->attribute) ? 'is-invalid' : '')
			, $this->attribute
			, $this->attribute
			, !empty($this->holder) ? $this->holder : (!empty($this->label) ? $this->label : $this->attribute)
			, $this->disabled
			, $this->required
			, $this->model->{'get' . ucfirst($this->attribute)}()
			, $this->model->getFirstError($this->attribute)
		);
	}

	/**
	 * make the field disabled
	 * @return $this
	 */
	public function disabled(): TextArea
	{
		$this->disabled = self::DISABLED;

		return $this;
	}

	/**
	 * make the field required
	 * @return $this
	 */
	public function required(): TextArea
	{
		$this->required = self::REQUIRED;

		return $this;
	}


	/**
	 * give a default value to the field
	 * @param string $default
	 * @return $this
	 */
	public function default(string $default): TextArea
	{
		if (!$this->model->{$this->attribute})
			$this->model->{$this->attribute} = $default;

		return $this;
	}

	/**
	 * @param string $string
	 * @return $this
	 */
	public function setHolder(string $string): TextArea
	{
		$this->holder = $string;

		return $this;
	}

	public function setLabel(string $string): TextArea
	{
		$this->label = $string;

		return $this;
	}

	public function setClass(string $string):TextArea
	{
		$this->class = $string;

		return $this;
	}
}
