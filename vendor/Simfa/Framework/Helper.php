<?php


namespace Simfa\Framework;


class helper
{

	/**
	 * helper constructor.
	 */
	public function __construct()
	{
		foreach (glob(Application::$ROOT_DIR . "/vendor/Simfa/Helper/*.php") as $filename)
		{
			include $filename;
		}
	}

}
