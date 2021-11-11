<?php

namespace vendor\FakeData;

use vendor\FakeData\src\FakeData;

class FakeDataFactory extends FakeData
{
	private static ?FakeDataFactory $instance = null;
	private array $types = ['person', 'text', 'media', 'model'];

	private function __construct()
	{

	}

	public static function create(): FakeDataFactory
	{
		if (self::$instance == null)
			self::$instance = new FakeDataFactory();

		return self::$instance;
	}

	public function __get($property)
	{
		foreach ($this->types as $type)
		{
			if (in_array($property, $this->$type) && $property != '_class') {
				$class = new $this->$type['_class']();
				return $class->$property();
			}
		}

		return "$property is not found in fakeData";
	}

	public function __call($method, $args)
	{
		foreach ($this->types as $type)
		{
			if (in_array($method, $this->$type) && $method != '_class') {
				$class = new $this->$type['_class']();
				return call_user_func_array([$class, $method], $args);
			}
		}

		return $method . " is not found in fakeData";
	}
}
