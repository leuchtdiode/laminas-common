<?php
namespace Common\Action;

use Common\Hydration\ArrayHydratable;
use Common\Hydration\ObjectToArrayHydratorProperty;

class Meta implements ArrayHydratable
{
	#[ObjectToArrayHydratorProperty]
	private int $offset;

	#[ObjectToArrayHydratorProperty]
	private int $count;

	#[ObjectToArrayHydratorProperty]
	private int $total;

	public static function create(): self
	{
		return new self();
	}

	public function getOffset(): int
	{
		return $this->offset;
	}

	public function setOffset(int $offset): Meta
	{
		$this->offset = $offset;
		return $this;
	}

	public function getCount(): int
	{
		return $this->count;
	}

	public function setCount(int $count): Meta
	{
		$this->count = $count;
		return $this;
	}

	public function getTotal(): int
	{
		return $this->total;
	}

	public function setTotal(int $total): Meta
	{
		$this->total = $total;
		return $this;
	}
}