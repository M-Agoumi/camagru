<?php

namespace vendor\FakeData\src;

class Generator
{

	/**
	 * @param array $array
	 * @return mixed
	 */
	public static function random(array $array)
	{
		return $array[array_rand($array)];
	}
}
