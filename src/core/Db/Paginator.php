<?php

namespace core\Db;

use core\Application;

abstract class Paginator
{
	public function paginate()
	{
		$this->totalRecords = $this->getCount();
		$this->currentPage = intval($_GET['page'] ?? 0) ?: NULL;
	}
}
