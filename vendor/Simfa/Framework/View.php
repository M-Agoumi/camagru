<?php


namespace Simfa\Framework;


use DirectoryIterator;
use Exception;

class View
{

	/**
	 * @var string|null
	 */
	private ?string $rootDir = null;

	/**
	 * @var bool|mixed|null
	 */
	private ?bool $isCacheEnabled;

	/**
	 * to know if it's a normal view file or system view file
	 * @var bool
	 */
	private bool $systemCall = false;

	/**
	 * init $isCacheEnabled from config/App.php
	 */
	public function __construct()
	{
		$config = Application::getAppConfig('view');
		$this->isCacheEnabled = $config['cache'];
	}


	/**
	 * @param string $view
	 * @param array $params
	 * @param bool $return
	 * @return string|null
	 */
	public function renderView(string $view, array $params = [], bool $return = false): ?string
	{
		$content = '';
		try {
			$this->rootDir = Application::$ROOT_DIR . '/';
			if (!$this->isCacheEnabled)
				return $this->setCacheContent($view, $params, true);
			try {
				$content = $this->getCacheContent($view, $params);
			} catch (Exception $e) {
				return $this->setCacheContent($view, $params, true);
			}

			if (!$content)
				$content = $this->setCacheContent($view, $params, $return);
		} catch (Exception $e) {
			Application::$APP->catcher->catch($e);
		}

		return $content;
	}

	/**
	 * @param string $view
	 * @param array $params
	 * @param bool $return
	 * @return string|null
	 * @throws Exception
	 */
	public function renderViewSystem(string $view, array $params = [], bool $return = false): ?string
	{
		$this->systemCall = true;

		return $this->renderView($view, $params, $return);
	}

	/**
	 * @throws Exception
	 */
	private function getCacheContent(string $view, $params): ?string
	{
		/** retrieve the original file */
		if ($this->systemCall)
			$srcFile = $this->rootDir . 'views/' . str_replace('.', '/', $view ) . '.gaster.php';
		else
			$srcFile = $this->rootDir . 'views/templates/' . str_replace('.', '/', $view ) . '.gaster.php';
		if (file_exists($srcFile))
			$hash = md5_file($srcFile);
		else
			throw new Exception($srcFile . ' is not found anymore');
		$file = $this->rootDir . 'var/cache/gaster/' . str_replace('/', '.', $view ) . '.endl.' . $hash . '.php';

		if (file_exists($file)) {
			if (is_readable($file)) {
				foreach ($params as $key => $param) {
					$$key = $param;
				}

				ob_start();
				include $file;

				return ob_get_clean();
			} else
				throw new Exception('permission denied to cached file');
		}

		return false;
	}

	/**
	 * @throws Exception
	 */
	private function setCacheContent(string $view, array $params, bool $return = false): ?string
	{
		$view = str_replace('.', '/', $view);
		if ($this->systemCall)
			$file = $this->rootDir . 'views/' . $view . '.gaster.php';
		else
			$file = $this->rootDir . 'views/templates/' . $view . '.gaster.php';

		/** load view from the file */
		if (file_exists($file))
			return $this->renderOnlyView([$file, $view], $params, $return);

		throw new Exception('view file not found: ' . $view);
	}

	/**
	 * @throws Exception
	 */
	private function renderOnlyView($view, array $params, bool $return): ?string
	{
		$content = file_get_contents($view[0]);

		$content = $this->preprocessContent($content);

		if (!$return) {
			$this->cacheContent($view, $content);
			return $this->renderView(str_replace('/', '.', $view[1]), $params);
		}

		return $this->renderProcessedData($content, $params);
	}

	private function preprocessContent($content)
	{
		/** remove comments */
		$content = preg_replace('({{--([\s\S]*?)--}})', '', $content);

		/** get layout if there is any */
		preg_match('(@layout\(.*?\))', $content, $layout);
		$content = preg_replace('(@layout\(.*?\))', '', $content);
		$layout = $this->loadLayout($layout[0] ?? false);

		/** find variables */
		preg_match_all("({{([\s\S]*?)}})", $content, $matches);
		foreach ($matches[1] as $match)
		{
			$ourVar =  str_replace(' ', '', $match);
			$content = str_replace('{{' . $ourVar . '}}', '<?=htmlspecialchars($'. $ourVar . ')?>', $content);
			$content = str_replace('{{ ' . $ourVar . ' }}', '<?=htmlspecialchars($'. $ourVar . ')?>', $content);
		}

		/** find if statements */
		preg_match_all('(@if( *)\(.*?\))', $content, $matches);
		foreach ($matches[0] as $match)
		{
			/** check if the 'ifStatement' has a closing tag otherwise throw error */
			preg_match('(' . $this->addBackSlash($match) . '([\s\S]*?)(@endif))', $content, $ifStatement);
			if (empty($ifStatement))
				die('the following if statement is missing closing bracket: ' . $match);

			/** make magic (make it php code XD) */
			preg_match('(\(.*\))', $match, $statement);
			$content = str_replace($match, '<?php if' . $statement[0] . ':?>', $content);
			preg_match_all('((@elseif)( *?)(\(.*\)))', $ifStatement[0], $elseStatements);

			foreach ($elseStatements[0] as $elseStatement)
			{
				preg_match('(\(.*\))', $elseStatement, $statement);
				$content = str_replace($elseStatement, '<?php elseif '. $statement[0] . ': ?>', $content);
			}
			$content = $this->str_replace_first('@else', '<?php else: ?>', $content);
			$content = $this->str_replace_first('@endif', '<?php endif; ?>', $content);
		}

		/** look for csrf tags */
		$content = str_replace('@csrf', '<?= \Simfa\Framework\Application::$APP->session->getCsrf() ?>', $content);

		/** merge layout with the view and return the result */
		$content = $this->layoutContentMerge($layout, $content);

		/** get include files and return our final masterpiece */
		return $this->getIncludeFiles($content);
	}

	private function getIncludeFiles(string $content): string
	{
		preg_match_all('(@include( *)\(.*?\))', $content, $matches);
		foreach ($matches[0] as $match) {
			preg_match('(\(.*\))', $match, $file);
			$includeFile = $this->rootDir . 'views/' .str_replace(['("', "('", '")', "')"], '', $file[0]);

			/** check if file exists */
			if (!file_exists($includeFile))
				die("We tried to include the following file from while rendering your page: " . $file[0] .
					"\nbut the file doesn't exist\n");

			/** set the file content instead of the include statement */
			$content = str_replace($match, '<?php include("' . $includeFile . '") ?>', $content);
		}

		return $content;
	}

	private function addBackSlash(string $string): string
	{
		$slashes = '';

		$i = 0;
		while ($i < strlen($string)){
			if ($string[$i] == '(' || $string[$i] == ')' || $string[$i] == '$')
				$slashes .= '\\';
			$slashes .= $string[$i];
			$i++;
		}

		return $slashes;
	}

	private function str_replace_first($from, $to, $content)
	{
		$from = '/'.preg_quote($from, '/').'/';

		return preg_replace($from, $to, $content, 1);
	}

	private function loadLayout($layout)
	{
		if (!$layout)
			return false;

		/** isolate the name of the layout */
		$layout = str_replace(['@layout(', '\'', '"', ')'], '', $layout);

		/** get our layout if it exists*/
		$file = $this->rootDir . 'views/layout/' . $layout . '.layout.php';
		ob_start();
		if (file_exists($file))
			include $file;
		else
			die('Could not load the layout, layout not found: ' . $layout);

		return ob_get_clean();
	}

	private function layoutContentMerge(string $layout, string $content)
	{
		/** if we don't have a layout we have nothing to do here */
		if (!$layout)
			return $content;

		/** find all yield sections */
		preg_match_all('(@yield\(.*?\))', $layout, $yields);

		/** replace each yield section with its view section */
		foreach ($yields[0] as $yield)
		{
			/** isolate the section name */
			$yieldName = preg_replace('(@yield\(([\'"]).*?)', '', $yield);
			$yieldName = str_replace(["')", '")'], '', $yieldName);

			/** fetch the yield section from our content */
			preg_match("(@section\((['\"])" . $yieldName . "(['\"])\)([\s\S]*?)@endsection)", $content, $match);

			/** remove gaster section declaration from the text */
			$match = preg_replace('(@section\(([\'"])' . $yieldName . '([\'"])\))', '', $match[0] ?? '');
			$match = preg_replace('(@endsection)', '', $match);
			$match = trim($match);

			/** inject the section into the layout */
			$layout = str_replace($yield, $match, $layout);
		}

		return $layout;
	}

	private function cacheContent($view, $content)
	{
		if ($this->systemCall)
			$originalFile = $this->rootDir . 'views/' . $view[1] . '.gaster.php';
		else
			$originalFile = $this->rootDir . 'views/templates/' . $view[1] . '.gaster.php';
		$view = str_replace('/', '.', $view[1]);
		$originalHash = md5_file($originalFile);

		/** delete any left cache file of this view */
		foreach (new DirectoryIterator($this->rootDir . 'var/cache/gaster') as $file) {
			if ($file->isFile()) {
				$fileName = $file->getFilename();
				if (substr($fileName, 0, strlen($view . ".endl")) === $view . ".endl") {
					unlink($this->rootDir . 'var/cache/gaster/'. $fileName);
				}
			}
		}

		/** create cache file */
		$cacheFileName = $this->rootDir . 'var/cache/gaster/' . $view . '.endl.' . $originalHash . '.php';
		$cachedFile = fopen($cacheFileName, "w") or die('could not open cache file for writing');

		/** write content to the file */
		fwrite($cachedFile, $content);
		/** close file */
		fclose($cachedFile);
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
		$protocol = Application::getEnvValue('SECURE') ? 'https://' : 'http://';
		$appUrl = Application::getEnvValue('APP_URL');
		$appPort = Application::$APP->request->port();

		$asset = $protocol . $appUrl;
		$asset .= $appPort ? ":" . $appPort : '';

		return $asset . "/" . $link;
	}

	private function renderProcessedData(string $content, $params): ?string
	{
		foreach ($params as $key => $param) {
			$$key = $param;
		}
		ob_start();
		eval('?>' . $content . '<?php');

		return ob_get_clean();
	}
}
