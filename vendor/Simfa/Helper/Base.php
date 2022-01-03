<?php

use Simfa\Framework\Application;

/**
 * @param $asset
 * @return string
 */
function asset($asset): string
{
	return Application::$APP->view->asset($asset);
}

function render(string $view, array $params = [])
{
	return Application::$APP->view->renderView($view, $params);
}

function route($path, $var = null): string
{
	return Application::path($path, $var);
}

function redirect($path = null)
{
	Application::$APP->response->redirect($path);
}

function lang(string $string):string
{
	return Application::$APP->lang($string);
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


