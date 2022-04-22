<?php

namespace Helper;

use Model\User;
use Simfa\Framework\Cookie;
use Simfa\Framework\Request;

class TimeHelper
{
	private Request $request;
	private Cookie $cookie;
	private User $user;

	/**
	 * @param Request $request
	 * @param Cookie $cookie
	 */
	public function __construct(Request $request, Cookie $cookie, User $user)
	{
		$this->request = $request;
		$this->cookie  = $cookie;
		$this->user    = $user;
	}

	/** convert from timestamp to human-readable time
	 * @param $time
	 * @return string
	 */
	public function humanTiming($time): string
	{

		if (is_string($time))
			$time = strtotime($time);
		$time = time() - $time; // to get the time since that moment
		$time = ($time < 1) ? 1 : $time;
		$tokens = array(
			31536000 => 'year',
			2592000 => 'month',
			604800 => 'week',
			86400 => 'day',
			3600 => 'hour',
			60 => 'minute',
			1 => 'second'
		);

		foreach ($tokens as $unit => $text) {
			if ($time < $unit) continue;
			$numberOfUnits = floor($time / $unit);
			return $numberOfUnits . ' ' . $text . (($numberOfUnits > 1) ? 's' : '');
		}
		return "0";
	}
}
