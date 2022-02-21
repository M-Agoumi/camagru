<?php

namespace Simfa\Framework\Db;

use Simfa\Framework\Application;
use Simfa\Framework\Session;

abstract class Paginator
{
	private Session $session;
	private bool    $firstCall = true;

	public function __construct()
	{
		$this->session = Application::$APP->session;
	}

	public function paginate(array $config = [], array $protected = []): array
	{
		/** @var $articlesNum int elements by page */
		$articlesNum = $config['articles'] ?? 10;
		$order = strtoupper($config['order']) ?? 'ASC';
		$this->totalRecords = $this->getCount();
		$this->currentPage = isset($config['autoPage'])  && $config['autoPage']
			? $this->getPage() : intval($_GET['page'] ?? $_POST['page'] ?? 1);
		$limit = ($this->currentPage - 1) * $articlesNum;
		$limit = $limit < 0 ? 0 : $limit;
		$limit = "$limit, " . ($articlesNum);
		$this->limit = $limit;
		$this->articlesByPage = $articlesNum;

		$result = $this->processData($this->findAll($limit, $order), $protected);

		if (empty($result) && $this->firstCall) {
			$this->getPage(true);
			return $this->paginate($config, $protected);
		}

		return $result;
	}

	private function processData(array $data, $protected): array
	{
		$protected = [...$protected, ...static::$protected];

		if (!empty($protected)) {
			foreach ($protected as $key) {
				for($i = 0; $i < count($data); $i++) {
					unset($data[$i][$key]);
				}
			}
		}

		return $data;
	}

	public function pages()
	{
		if (isset($this->totalRecords)) {
			$totalPages = ceil($this->totalRecords / $this->articlesByPage);

			$pages = [];
			for ($i = 1; $i <= $totalPages; $i++) {
				if ($this->currentPage == $i)
					$pages[$i] = ['active' => $i];
				else
					$pages[$i] = $i;
			}

			return $pages;
		}
		return 0;
	}

	private function getPage(bool $reset = false)
	{
		if (!$reset) {
			if ($this->session->get('page')) {
				$this->session->set('page', $this->session->get('page') + 1);
				return $this->session->get('page');
			}
		} else {
			$this->session->set('page', 0);
			return 0;
		}

		$this->session->set('page', 1);

		return 1;
	}
}
