<?php

namespace Simfa\Form;

use Simfa\Model\Model;

class Select
{
	public const DISABLED = 'disabled="disabled"';
	public const REQUIRED = 'required="required"';

	public Model $model;
	public string $attribute;
	public string $label;
	public string $disabled;
	public string $required;
	public string $default;
	public string $options;
	private ?string $show;
	private $elements;

	public function __construct(Model $model, string $attribute, $elements, $show)
	{
		$this->model = $model;
		$this->attribute = $attribute;
		$this->disabled = '';
		$this->required = '';
		$this->default = '';
		$this->show = $show;
		$this->elements = $elements;
	}

	public function __toString(): string
	{
		return sprintf('
		<div class="row">
			<div class="col-25">
				<label for="%s">%s</label>
			</div>
			<div class="col-75">
				<select class="%s" id="%s" name="%s" %s %s>
					%s
				</select>
				<div class="invalid-feedback">
					%s
				</div>
			</div>
		</div>
		',  $this->attribute
			, !empty($this->label) ? $this->label : ucfirst($this->attribute)
			, $this->model->hasError($this->attribute) ? 'is-invalid' : ''
			, $this->attribute
			, $this->attribute
			, $this->disabled
			, $this->required
			, $this->toString($this->elements)
			, $this->model->getFirstError($this->attribute)
		);
	}

	/**
	 * @param array|Model $elements
	 * @return string
	 */
	public function toString(array|Model $elements): string
	{
		$string = '';

		if (!is_array($elements)) {
			$primary = $elements->primaryKey();
			$options = $elements->findAll();

			foreach ($options as $option) {
				$string .= "<option value='" . $option[$primary] . "'";
				$string .= $this->model->{'get' . ucfirst($this->attribute)}() == $option[$primary] ? 'selected' : '';
				if ($this->show)
					$string .= ">" . $option[$this->show] . "</option>" . PHP_EOL;
				else
					$string .= ">" . $option[$this->attribute] . "</option>" . PHP_EOL;
			}
		} else {
			foreach ($elements as $value => $element) {
				error_log('default: ' . gettype($this->default) . "\nvalue: " . $value . "\n", 3, '/var/www/camagru/runtime/logs/logs.log');
				if ($this->default == $value || $this->model->{$this->attribute} == $value)
					$string .= "<option value='" . $value . "' selected='selected'>" . $element . "</option>" . PHP_EOL;
				else
					$string .= "<option value='" . $value . "'>" . $element . "</option>" . PHP_EOL;
			}
			error_log("------------------------------\n", 3, '/var/www/camagru/runtime/logs/logs.log');
		}


		return $string;
	}

	/**
	 * @param string $label
	 * @return Select
	 */
	public function setLabel(string $label)
	{
		$this->label = $label;

		return $this;
	}

    public function setDefault(string $string)
    {
		$this->default = $string;

		return $this;
    }
}
