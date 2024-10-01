<?php
declare(strict_types=1);

namespace Common\Dto\Provide;

use Common\Db\FilterChain;

class HandleFilterParams
{
	/**
	 * @var FilterItem[]
	 */
	private array $filter;

	private FilterChain $filterChain;

	public static function create(): static
	{
		return new static();
	}

	/**
	 * @return FilterItem[]
	 */
	public function getFilter(): array
	{
		return $this->filter;
	}

	/**
	 * @param FilterItem[] $filter
	 */
	public function setFilter(array $filter): HandleFilterParams
	{
		$this->filter = $filter;
		return $this;
	}

	public function getFilterChain(): FilterChain
	{
		return $this->filterChain;
	}

	public function setFilterChain(FilterChain $filterChain): HandleFilterParams
	{
		$this->filterChain = $filterChain;
		return $this;
	}
}
