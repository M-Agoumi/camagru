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
	 * @return string|string[]
	 */
	public function renderView(string $view, array $params = [], array $layParams = [])
	{
		$template = $this->layoutContent($layParams);
		$view = $this->renderOnlyView($view, $params);

		/** since the title is present in every page I thought about putting here */
		$template = str_replace('{{ title }}', $layoutParams['title'] ??
							Application::getEnvValue('appName'), $template);
		return str_replace('{{ body }}', $view, $template);
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
		$layout = Application::$APP->controller->layout ?? 'main';
		ob_start();
		include Application::$ROOT_DIR . "/views/layout/$layout.layout.php";
		$output = ob_get_clean();
		foreach ($params as $key => $param) {
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

		$viewFile = Application::$ROOT_DIR . "/views/$view.blade.php";
		if (file_exists($viewFile))
			include $viewFile;
		else
			echo "the view <b>$view</b> is not found";

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

	public function asset(string $link): string
	{
		$protocol = Application::getEnvValue('secure') ? 'https://' : 'http://';
		$appUrl = Application::getEnvValue('appURL');
		$appPort = Application::$APP->request->port();

		$asset = $protocol . $appUrl;
		$asset .= $appPort ? ":" . $appPort : '';

		return $asset . "/" . $link;
	}
}
