<?php

namespace core\CLI;

interface BaseCommandInterface
{
	/**
	 * Show command help list
	 * @return string
	 */
	public static function helper(string $command): string;
}
