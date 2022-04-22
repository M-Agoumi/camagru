<?php

use Simfa\Framework\Application;

if (! function_exists('asset')) {
	/**
	 * @param $asset
	 * @return string
	 */
	function asset($asset): string
	{
		return Application::$APP->view->asset($asset);
	}
}

if (! function_exists('render')) {
	/**
	 * render a view file
	 * @param string $view file name to be rendered [Ps: can't exceed 612 characters]
	 * @param array $params
	 * @return string|null
	 */
	function render(string $view, array $params = []): ?string
	{
		return Application::$APP->view->renderView($view, $params);
	}
}

if (! function_exists('route')) {
	/**
	 * @param $path
	 * @param $var
	 * @return string
	 * @throws Exception
	 */
	function route($path, $var = null): string
	{
		return Application::path($path, $var);
	}
}

if (! function_exists('redirect')) {
	/**
	 * @param $path
	 * @return void
	 */
	function redirect($path = null)
	{
		Application::$APP->response->redirect($path);
	}
}

if (! function_exists('lang')) {
	/**
	 * @param string $string
	 * @return string
	 */
	function lang(string $string): string
	{
		return Application::$APP->lang($string);
	}
}
