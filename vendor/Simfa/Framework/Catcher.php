<?php

namespace Simfa\Framework;

use Exception;

class Catcher
{
	public Application $app;

	public function __construct()
	{
		$this->app = Application::$APP;
	}

	public function catch(Exception $e)
	{
		$this->app->response->setStatusCode($e->getCode());

		if ($this->app->interface != 'api')
			$this->webLog($e);
		else
			$this->apiLog($e);
	}

	private function webLog(Exception $e)
	{
		$clear = ob_get_clean();
		$message = '';
		if ($e->getCode() != 404) {
			ob_start();
			echo "<pre>";
			var_dump($e);
			print_r($e->getTraceAsString());
			$file = $e->getFile();
			$lines = file($file); //file in to an array
			$line = $e->getLine();
			echo "<br>code:<br>" . ($line - 1) . "\t" .
				$lines[$line - 1] . "<br>$line\t" .
				$lines[$line ] . "<br>" . ($line + 1) . "\t" .
				$lines[$line + 1];
			echo "<br><br>on file " . $file . " line " . $line;
			echo "</pre>";
			$message = ob_get_clean();
		}

		if ($this->app->getDotEnv()['env'] != 'dev') {
			$codeView = 'error/__' . $e->getCode();
			if (file_exists(Application::$ROOT_DIR . '/views/' . $codeView . '.gaster.php'))
				echo $this->app->view->renderView('error/__' . $e->getCode(), ['e' => $e, 'title' => $e->getCode()]);
			else
				echo $this->app->view->renderView('error/__500', ['title' => '500 Internal Server Error']);
		} else
			echo $this->app->view->renderView('error/__error', ['e' => $e, ['title' => $e->getCode()]]);
	}

	private function apiLog(Exception $e)
	{
		echo $e->getCode();
	}
}
