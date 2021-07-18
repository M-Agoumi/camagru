<?php

namespace core\Exception;

use core\Application;

class ExpiredException extends \Exception
{
    protected $message;
    protected $code;

	/**
	 * ExpiredException constructor.
	 * @param string $message
	 * @param int $code
	 */
	public function __construct(string $message = 'This page has expired', int $code = 401)
	{
		$this->message = $message;
		$this->code = $code;
	}
}
