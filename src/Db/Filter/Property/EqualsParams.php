<?php
declare(strict_types=1);

namespace Common\Db\Filter\Property;

use Doctrine\ORM\Query\Expr\Func;
use Doctrine\ORM\QueryBuilder;

class EqualsParams extends BaseParams
{
	private array $values;
	private bool  $not = false;

	public static function create(): static
	{
		return new static();
	}

	public function getComparison(QueryBuilder $queryBuilder, string $field): Func
	{
		$valuesParam = uniqid('vp');

		$expr = $queryBuilder->expr();

		$queryBuilder->setParameter($valuesParam, $this->values);

		return $this->not
			? $expr->notIn($field, ':' . $valuesParam)
			: $expr->in($field, ':' . $valuesParam);
	}

	public function getValues(): array
	{
		return $this->values;
	}

	public function setValues(array $values): EqualsParams
	{
		$this->values = $values;
		return $this;
	}

	public function isNot(): bool
	{
		return $this->not;
	}

	public function setNot(bool $not): EqualsParams
	{
		$this->not = $not;
		return $this;
	}
}
