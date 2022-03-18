<?php
namespace Common\Db;

class FilterChain
{
	/**
	 * @var Filter[]
	 */
	private array $filters = [];

	public static function create(): self
	{
		return new self();
	}

	public function addFilter(Filter $filter) : FilterChain
	{
		$this->filters[] = $filter;

		return $this;
	}

	/**
	 * @return Filter[]
	 */
	public function getFilters(): array
	{
		return $this->filters;
	}
}