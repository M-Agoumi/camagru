<?php

namespace Simfa\Framework\Db;

use Simfa\Framework\Application;
use Simfa\Framework\Session;

abstract class Paginator
{
	/**
	 * @var bool
	 */
	private bool    $firstCall = true;

	/**
	 * @param array $config
	 * @param array $protected
	 * @return array
	 */
	public function paginate(array $config = [], array $protected = []): array
	{
		/** @var $articlesNum int elements by page */
		$articlesNum = $config['articles'] ?? 10;
		$order = strtoupper($config['order'] ?? 'ASC');
		$this->totalRecords = $this->getCount();
		if (isset($config['autoPage'])  && $config['autoPage'])
			$this->currentPage = $this->getPage();
		else
			$this->currentPage = $config['page'] ?? intval($_GET['page'] ?? $_POST['page'] ?? 1);

		$limit = ($this->currentPage - 1) * $articlesNum;
		$limit = $limit < 0 ? 0 : $limit;
		$limit = "$limit, " . ($articlesNum);
		$this->limit = $limit;
		$this->articlesByPage = $articlesNum;

		$result = $this->processData($this->findAll($limit, $order), $protected);


		if (empty($result) && $this->firstCall) {
			$this->firstCall = false;
			$this->getPage(true);
			return $this->paginate($config, $protected);
		}

		return $result;
	}

	/**
	 * @param array $data
	 * @param $protected
	 * @return array
	 */
	private function processData(array $data, $protected): array
	{
		$protected = [...$protected, ...static::$protected];

		if (!empty($protected) && !in_array('allow_all', $protected)) {
			foreach ($protected as $key) {
				for($i = 0; $i < count($data); $i++) {
					unset($data[$i][$key]);
				}
			}
		}

		return $data;
	}

	/**
	 * @return array|int
	 */
	public function pages(): array|int
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

	/**
	 * @param bool $reset
	 * @return int|mixed
	 */
	private function getPage(bool $reset = false)
	{
		if (!$reset) {
			if ($this->getSession()->get('page')) {
				$this->getSession()->set('page', $this->getSession()->get('page') + 1);
				return $this->getSession()->get('page');
			}
		} else {
			$this->getSession()->set('page', 0);
			return 0;
		}

		$this->getSession()->set('page', 1);

		return 1;
	}

	/**
	 * @return Session|null
	 */
	private function getSession(): ?Session
	{
		return Application::$APP->session;
	}
}
