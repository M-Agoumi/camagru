<?php

namespace Simfa\Framework\CLI;

abstract class BaseCommand implements BaseCommandInterface
{
	/**
	 * @var string $command the command Name
	 */
	protected static string $command = '';

	/** the help instruction command printing function
	 * @param string $subCommand
	 * @return string
	 */
	protected static function printCommand(string $subCommand): string
	{
		return str_pad(YELLOW . static::$command . ':' . GREEN . $subCommand . RESET , 46, ' ');
	}

	/** write a console message for interactive shell
	 * @param $text
	 * @param string $color
	 * @param bool $newline
	 * @param bool $reset
	 */
	protected function write($text, string $color = "", bool $newline = false, bool $reset = true) {

		switch ($color) {
			case "warning":
				echo YELLOW;
				break;
			case 'danger':
				echo RED;
				break;
			case 'info':
				echo BLUE;
				break;

			default:
				echo GREEN;
				break;
		}

		echo $text;
		if ($newline)
			echo PHP_EOL;
		if ($reset)
			echo RESET;
	}
}
