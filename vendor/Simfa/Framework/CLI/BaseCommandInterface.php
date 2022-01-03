<?php

namespace Simfa\Framework\CLI;

interface BaseCommandInterface
{
	/**
	 * Show command help list
	 * @param string $command
	 * @return string
	 */
	public static function helper(string $command): string;
}
