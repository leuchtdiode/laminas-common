<?php
namespace Common\Db\Filter\Distance;

class ColumnOrValue
{
	private ?string $column = null;
	private ?float  $value  = null;

	public static function create(): ColumnOrValue
	{
		return new static();
	}

	public function getColumn(): ?string
	{
		return $this->column;
	}

	public function setColumn(?string $column): ColumnOrValue
	{
		$this->column = $column;
		return $this;
	}

	public function getValue(): ?float
	{
		return $this->value;
	}

	public function setValue(?float $value): ColumnOrValue
	{
		$this->value = $value;
		return $this;
	}
}