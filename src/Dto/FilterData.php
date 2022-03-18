<?php
namespace Common\Dto;

use Common\Db\FilterChain;
use Common\Db\OrderChain;

class FilterData
{
	private FilterChain $filterChain;

	private ?OrderChain $orderChain = null;

	private int $offset = 0;

	private int $limit = PHP_INT_MAX;

	/**
	 */
	public function __construct()
	{
		$this->filterChain = FilterChain::create();
	}

	public static function create(): self
	{
		return new self();
	}

	public function getFilterChain(): FilterChain
	{
		return $this->filterChain;
	}

	public function setFilterChain(FilterChain $filterChain): FilterData
	{
		$this->filterChain = $filterChain;
		return $this;
	}

	public function getOrderChain(): ?OrderChain
	{
		return $this->orderChain;
	}

	public function setOrderChain(?OrderChain $orderChain): FilterData
	{
		$this->orderChain = $orderChain;
		return $this;
	}

	public function getOffset(): int
	{
		return $this->offset;
	}

	public function setOffset(int $offset): FilterData
	{
		$this->offset = $offset;
		return $this;
	}

	public function getLimit(): int
	{
		return $this->limit;
	}

	public function setLimit(int $limit): FilterData
	{
		$this->limit = $limit;
		return $this;
	}
}