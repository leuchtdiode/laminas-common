<?php
declare(strict_types=1);

namespace Common\Dto\Provide;

use Common\Db\FilterChain;

class HandleFilterResult
{
	private FilterChain $filterChain;

	public function getFilterChain(): FilterChain
	{
		return $this->filterChain;
	}

	public function setFilterChain(FilterChain $filterChain): void
	{
		$this->filterChain = $filterChain;
	}
}
