<?php
/* ************************************************************************************************ */
/*                                                                                                  */
/*                                                        :::   ::::::::   ::::::::  :::::::::::    */
/*   Model.php                                         :+:+:  :+:    :+: :+:    :+: :+:     :+:     */
/*                                                      +:+         +:+        +:+        +:+       */
/*   By: magoumi <magoumi@student.1337.ma>             +#+      +#++:      +#++:        +#+         */
/*                                                    +#+         +#+        +#+      +#+           */
/*   Created: 2021/03/18 12:10:52 by magoumi         #+#  #+#    #+# #+#    #+#     #+#             */
/*   Updated: 2021/03/18 12:10:52 by magoumi      ####### ########   ########      ###.ma           */
/*                                                                                                  */
/* ************************************************************************************************ */

/**
 * Class Model
 */

abstract class Model
{
    public const RULE_REQUIRED = 'required';
    public const RULE_EMAIL = 'email';
    public const RULE_MIN = 'min';
    public const RULE_MAX = 'max';
    public const RULE_UNIQUE = 'unique';
    /**
     * @var array
     */
    public array $errors = [];

    /**
     * @param array $data
     */
    public function loadData(array $data)
	{
		foreach ($data as $key => $value) {
			if (property_exists($this, $key)) {
				$this->{$key} = $value;
			}
		}
	}

    /**
     * @return array
     */
    public abstract function rules(): array;

    /**
     * @return int
     */
    public function validate(): int
    {
	    foreach ($this->rules() as $attribute => $rules) {
	        $value = $this->{$attribute};
	        foreach ($rules as $rule) {
                $ruleName = $rule;
                // if $rule name is an array so we dont have an error in next lines
                if (!is_string($ruleName)) {
                    $ruleName = $ruleName[0];
                }
                // required? then check for null
                if ($ruleName === self::RULE_REQUIRED && !$value)
                    $this->addError($attribute, self::RULE_REQUIRED);
                // email? fine check if it's a valid email syntax
                if ($ruleName === self::RULE_EMAIL && !filter_var($value, FILTER_VALIDATE_EMAIL))
                    $this->addError($attribute, self::RULE_EMAIL);
                // min? check the strlen()
                if ($ruleName === self::RULE_MIN && strlen($value) < $rule['min'])
                    $this->addError($attribute, self::RULE_MIN, $rule);
                // max? check the strlen()
                if ($ruleName === self::RULE_MAX && strlen($value) > $rule['max'])
                    $this->addError($attribute, self::RULE_MAX, $rule);
            }
        }

		return empty($this->errors);
	}

    /**
     * @param string $attribute
     * @param string $rule
     * @param array $params
     */
    private function addError(string $attribute, string $rule, array $params = [])
    {
        $message = $this->errorMessages()[$rule] ?? '';
        foreach ($params as $key => $value) {
            $message = str_replace("{{$key}}", $value, $message);
        }
        $this->errors[$attribute][] = $message;
    }

	public function hasError($attribute)
	{
		return $this->errors[$attribute] ?? false;
	}

	public function getFirstError($attribute): string
	{
		return $this->errors[$attribute][0] ?? '';
	}

    /**
     * @return string[]
     */
    private function errorMessages(): array
    {
        return [
            self::RULE_REQUIRED => $this->lang('RULE_REQUIRED'),
            self::RULE_EMAIL => $this->lang('RULE_EMAIL'),
            self::RULE_MIN => $this->lang('RULE_MIN'),
            self::RULE_MAX => $this->lang('RULE_MAX'),
            self::RULE_UNIQUE => $this->lang('RULE_UNIQUE'),
        ];
    }

    public function lang(string $key): string
    {
        return Application::$APP->lang($key);
    }
}