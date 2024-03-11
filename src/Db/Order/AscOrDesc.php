<?php
namespace Common\Db\Order;

use Common\Db\Order;
use Doctrine\ORM\QueryBuilder;
use RuntimeException;

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
		if (!in_array(strtolower($this->direction), [ 'asc', 'desc' ]))
		{
			throw new RuntimeException('Invalid direction given');
		}

		$queryBuilder->addOrderBy(
			$this->getField(),
			$this->direction
		);
	}
}