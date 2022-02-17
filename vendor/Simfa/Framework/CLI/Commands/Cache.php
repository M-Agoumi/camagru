<?php

namespace Simfa\Framework\CLI\Commands;

use Simfa\Framework\CLI\BaseCommand;
use Simfa\Framework\CLI\CLIApplication;
use DirectoryIterator;

class Cache extends BaseCommand
{

	/**
	 * @var string
	 */
	protected static string $command = 'cache';

	/**
	 * @param array $params
	 * @return string
	 */
	public function clear(array $params = []): string
	{
		$path = CLIApplication::$ROOT_DIR . 'var/cache/gaster';
		$files = new DirectoryIterator($path);

		/** delete template engine cache files */
		echo "template engine" . PHP_EOL;
		foreach($files as $cacheFile)
		{
			if($cacheFile->isDot() || $cacheFile == '.gitignore')
				continue;

			if (unlink($path . '/' . $cacheFile) && (in_array('-v', $params) || in_array('--visual', $params)))
				echo RED . 'removed: ' . RESET . $cacheFile . PHP_EOL;
		}

		return '';
	}

	static function helper(): string
	{
		$helperMessage  = RED . self::$command . RESET . PHP_EOL;
		$helperMessage .= self::printCommand('clear') . "clear all cache" . PHP_EOL;
		$helperMessage .= CYAN ."     -v --visual" . RESET . "\t\tprint deleted files";

		return $helperMessage;
	}
}
