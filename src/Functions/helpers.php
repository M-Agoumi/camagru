<?php

use core\Application;
use core\View;

/**
 * @param $asset
 * @return string
 */
function asset($asset): string
{
	return View::asset($asset);
}

function render(string $view, array $params = [], array $layParams = [])
{
	return Application::$APP->view->renderView($view, $params, $layParams);
}

function route($path): string
{
	return Application::path($path);
}

/** convert from timestamp to human readable time
 * @param $time
 * @return string
 */
function humanTiming($time): string
{

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
