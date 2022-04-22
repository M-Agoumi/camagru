<?php


namespace Simfa\Framework;


class Helper
{
	/**
	 * @var Helper|null
	 */
	private static ?Helper $Helper = null;
	private static array $HelpersInstances = [];

	/**
	 * helper constructor.
	 */
	private function __construct()
	{
		/** @var string $filename framework helpers to include */
		foreach (glob(Application::$ROOT_DIR . "/vendor/Simfa/Helper/*.php") as $filename)
		{
			include $filename;
		}
	}

	/**
	 * @return Helper|null
	 */
	public static function initHelper(): ?Helper
	{
		if (!self::$Helper)
			self::$Helper = new Helper();
		return self::$Helper;
	}

	/**
	 * @param $helper
	 * @return mixed
	 */
	public static function getHelper($helper)
	{
		if (! isset(self::$HelpersInstances[$helper]))
			self::$HelpersInstances[$helper] = Application::$APP->injector->getInstance($helper);

		return self::$HelpersInstances[$helper];
	}
}

