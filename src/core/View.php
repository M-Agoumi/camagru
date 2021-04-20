<?php


namespace core;


class View
{
	/**
	 * mix the template with the asked $view
	 *
	 * @param $view string name of the view we want to compile with the template
	 * @param array $params
	 * @param array $layParams
	 * @return false|string|string[]
	 */
	public function renderView(string $view, array $params = [], array $layParams = [])
	{
		/** todo pass params to the layout too */
		$layout = $this->layoutContent($layParams);
		$view = $this->renderOnlyView($view, $params);
		/** since the title is present in every page I thought about putting here */
		$layout = str_replace('{{ title }}', $layoutParams['title'] ?? Application::getEnvValue('appName'), $layout);
		return str_replace('{{ body }}', $view, $layout);
	}

	/**
	 * @param string $content content to be rendered in the template
	 * @return false|string|string[]
	 */
	public function renderContent(string $content)
	{
		$layout = $this->layoutContent();
		return str_replace('{{ body }}', $content, $layout);
	}

	/**
	 * @param array $params
	 * @return false|string the template content
	 */

	public function layoutContent(array $params = [])
	{
//        foreach ($params as $key => $param) {
//            $$key = $param;
//        }
//        if (!isset($title))
//            $title = Application::$APP->getEnvValue('appName') ?? 'Please Add a default title to .env as appName';
		$layout = Application::$APP->controller->layout ?? 'main';
		ob_start();
		include_once Application::$ROOT_DIR . "/views/layout/$layout.layout.php";
		$output = ob_get_clean();
		foreach ($params as $key => $param) {
//            echo '{{' .$key . "}} => " . $param . "<br>";
			$output = str_replace('{{ ' . $key . ' }}', $param, $output);
			$output = str_replace('{{' . $key . '}}', $param, $output);
		}
		return $output;
	}

	/**
	 * @param $view string the wanted view
	 * @param array $params
	 * @return string|null the view content
	 */
	protected function renderOnlyView(string $view, array $params): ?string
	{
		foreach ($params as $key => $param) {
			$$key = $param;
		}
		ob_start();
		include_once Application::$ROOT_DIR . "/views/$view.blade.php";
		return ob_get_clean();
	}

	/**
	 * get the value of the key from the language used
	 * @param string $key
	 * @return string
	 */
	public function lang(string $key): string
	{
		return Application::$APP->lang($key);
	}
}