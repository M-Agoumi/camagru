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

namespace Simfa\Model;

use Simfa\Framework\Application;
use Simfa\Framework\Db\DbModel;
use Simfa\Framework\Db\Paginator;

/**
 * Class Model
 */

abstract class Model extends Paginator
{
    public const RULE_REQUIRED = 'required';
    public const RULE_EMAIL = 'email';
    public const RULE_MIN = 'min';
    public const RULE_MAX = 'max';
    public const RULE_UNIQUE = 'unique';
    public const RULE_WRONG = 'wrong';
    /**
     * @var array to save errors to obtain later to show in the form
     */
    public array $errors = [];

    /**
     * load data from the form to its model
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
     * the rules should be respect by each child model
     * @return array
     */
    public abstract function rules(): array;

    /**
     * validate the rules
     * return true if no error found
     * return false in case of any error found and save it to $this->errors
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
                    $this->addErrorForRules($attribute, self::RULE_REQUIRED);
                // email? fine check if it's a valid email syntax
                if ($ruleName === self::RULE_EMAIL && !filter_var($value, FILTER_VALIDATE_EMAIL))
                    $this->addErrorForRules($attribute, self::RULE_EMAIL);
                // min? check the strlen()
                if ($ruleName === self::RULE_MIN && strlen($value) < $rule['min'])
                    $this->addErrorForRules($attribute, self::RULE_MIN, $rule);
                // max? check the strlen()
                if ($ruleName === self::RULE_MAX && strlen($value) > $rule['max'])
                    $this->addErrorForRules($attribute, self::RULE_MAX, $rule);

				// is it unique? let's check our records
                if ($ruleName === self::RULE_UNIQUE) {
                	/** @var $className DbModel */
                	$className  = $rule['class'];
                	$uniqueAttr = $rule['attribute'] ?? $attribute;
                	$primaryKey = $this->primaryKey();
					$className  = new $className();
					$className->getOneBy($uniqueAttr, $value);

                	if (($this->$attribute == $className->$attribute) && $this->{$primaryKey} != $className->{$primaryKey})
                		$this->addErrorForRules($attribute, self::RULE_UNIQUE, ['field' => ucfirst($attribute)]);
                }
            }
        }

		return empty($this->errors);
	}

    /**
     * add error message to it's according field
     * @param string $attribute
     * @param string $rule
     * @param array $params
     */
    private function addErrorForRules(string $attribute, string $rule, array $params = [])
    {
        $message = $this->errorMessages()[$rule] ?? '';
        foreach ($params as $key => $value) {
            $message = str_replace("{{$key}}", $value, $message);
        }
        $this->errors[$attribute][] = $message;
    }
	
	public function addError(string $attribute, string $message)
	{
		$this->errors[$attribute][] = $message;
	}

    /**
     * check if an attribute has an error
     * return it if true or false if not
     * @param $attribute
     * @return array|bool
     */
    public function hasError($attribute)
    {
		return $this->errors[$attribute] ?? false;
	}

    /**
     * return the first error of the field
     * @param $attribute
     * @return string
     */
    public function getFirstError($attribute): string
	{
		return $this->errors[$attribute][0] ?? '';
	}

    /** get the error message from the lang used
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
            self::RULE_WRONG => $this->lang('RULE_WRONG'),
        ];
    }

    /**
     * get the value of the key from the language used
     * @param string $key
     * @return string
     */
    public function lang(string $key): string
    {
        return Application::$APP->lang($key);
    }
}
