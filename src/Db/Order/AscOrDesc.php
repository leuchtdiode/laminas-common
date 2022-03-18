<?php
namespace Common\Db\Order;

use Common\Db\Order;
use Doctrine\ORM\QueryBuilder;

abstract class AscOrDesc implements Order
{
	protected string $direction;

	private function __construct(string $direction)
	{
		$this->direction = $direction;
	}

	abstract protected function getField(): string;

	public static function withDirection(string $direction): static
	{
		return new static($direction);
	}

	public static function asc(): static
	{
		return new static('ASC');
	}

	public static function desc(): static
	{
		return new static('DESC');
	}

	protected function getDirection(): string
	{
		return $this->direction;
	}

	public function addOrder(QueryBuilder $queryBuilder): void
	{
		$queryBuilder->addOrderBy(
			$this->getField(),
			$this->direction
		);
	}
}