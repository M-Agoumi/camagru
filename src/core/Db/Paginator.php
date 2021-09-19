<?php

namespace core\Db;

use core\Application;

abstract class Paginator
{
	public function paginate(array $config = [])
	{
		/** @var $articlesNum int elements by page */
		$articlesNum = $config['articles'] ?? 10;
		$this->totalRecords = $this->getCount();
		$this->currentPage = intval($_GET['page'] ?? 1);
		$limit = ($this->currentPage - 1) * $articlesNum;
		$limit = $limit < 0 ? 0 : $limit;
		$limit = "$limit, " . ($articlesNum);
		$this->limit = $limit;
		$this->articlesByPage = $articlesNum;

		return $this->findAll($limit);
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
}
