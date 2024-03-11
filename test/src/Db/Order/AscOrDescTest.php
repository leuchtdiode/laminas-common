<?php

namespace CommonTest\Db\Order;

use CommonTest\Base;
use CommonTest\EntityManagerMockTrait;
use Doctrine\ORM\QueryBuilder;
use RuntimeException;

class AscOrDescTest extends Base
{
	use EntityManagerMockTrait;

	/**
	 * @dataProvider invalidDirections
	 */
	public function test_invalid_order($direction): void
	{
		$queryBuilder = new QueryBuilder($this->getEntityManagerMock());

		$this->expectException(RuntimeException::class);

		AscOrDescOrder::withDirection($direction)
			->addOrder($queryBuilder);
	}

	/**
	 * @dataProvider validDirections
	 */
	public function test_add_order_asc($direction, $expectedQuery): void
	{
		$queryBuilder = new QueryBuilder($this->getEntityManagerMock());

		AscOrDescOrder::withDirection($direction)
			->addOrder($queryBuilder);

		$this->assertEquals(
			$expectedQuery,
			$queryBuilder
				->getQuery()
				->getDQL()
		);
	}

	public static function invalidDirections(): array
	{
		return [
			[ 'ascx' ],
			[ 'xasc' ],
			[ 'asc%25%27%20AND%202*3*8=6*8%20AND%20%27z6ix%27!=%27z6ix%25' ],
			[ 'blubb' ],
			[ 'null' ],
		];
	}

	public static function validDirections(): array
	{
		return [
			[ 'asc', 'SELECT ORDER BY t.irrelevant asc' ],
			[ 'desc', 'SELECT ORDER BY t.irrelevant desc' ],
			[ 'ASC', 'SELECT ORDER BY t.irrelevant ASC' ],
			[ 'DESC', 'SELECT ORDER BY t.irrelevant DESC' ],
		];
	}
}