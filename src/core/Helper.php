<?php


namespace core;


class helper
{

	/**
	 * helper constructor.
	 */
	public function __construct()
	{
		foreach (glob(Application::$ROOT_DIR . "/src/Functions/*.php") as $filename)
		{
			include $filename;
		}
	}

}
