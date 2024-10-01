<?php
declare(strict_types=1);

namespace Common\Db\Filter\Property;

use Doctrine\ORM\QueryBuilder;

class NullParams extends BaseParams
{
	public function __construct(
		private readonly bool $isNull
	)
	{
	}

	public static function isNull(): static
	{
		return new static(true);
	}

	public static function isNotNull(): static
	{
		return new static(false);
	}

	public function getComparison(QueryBuilder $queryBuilder, string $field): string
	{
		$expr = $queryBuilder->expr();

		return $this->isNull
			? $expr->isNull($field)
			: $expr->isNotNull($field);
	}
}
