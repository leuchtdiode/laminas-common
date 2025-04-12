<?php
namespace Common\Db\Filter;

use Common\Db\Filter;
use Doctrine\ORM\QueryBuilder;

abstract class Number implements Filter
{
	const EQUALS              = 'eq';
	const GREATER_THAN        = 'gt';
	const GREATER_THAN_EQUALS = 'gte';
	const LESS_THAN           = 'lt';
	const LESS_THAN_EQUALS    = 'lte';

	private string $type;

	private mixed $parameter;

	abstract protected function getField(): string;

	protected function getParameterName(): ?string
	{
		return null;
	}

	private function __construct(string $type, mixed $parameter = null)
	{
		$this->type      = $type;
		$this->parameter = $parameter;
	}

	public static function equals(mixed $parameter): static
	{
		return new static(self::EQUALS, $parameter);
	}

	public static function greaterThan(mixed $parameter): static
	{
		return new static(self::GREATER_THAN, $parameter);
	}

	public static function greaterThanEquals(mixed $parameter): static
	{
		return new static(self::GREATER_THAN_EQUALS, $parameter);
	}

	public static function lessThan(mixed $parameter): static
	{
		return new static(self::LESS_THAN, $parameter);
	}

	public static function lessThanEquals(mixed $parameter): static
	{
		return new static(self::LESS_THAN_EQUALS, $parameter);
	}

	public function addClause(QueryBuilder $queryBuilder): void
	{
		$expr = $queryBuilder->expr();

		// parameter name is optional now for child, uses randomized string by default
		$parameterName = $this->getParameterName() ?? uniqid('p');

		switch ($this->type)
		{
			case self::EQUALS:

				$queryBuilder
					->andWhere(
						$expr->eq($this->getField(), ':' . $parameterName)
					)
					->setParameter($parameterName, $this->parameter);

				break;

			case self::GREATER_THAN:

				$queryBuilder
					->andWhere(
						$expr->gt($this->getField(), ':' . $parameterName)
					)
					->setParameter($parameterName, $this->parameter);

				break;

			case self::GREATER_THAN_EQUALS:

				$queryBuilder
					->andWhere(
						$expr->gte($this->getField(), ':' . $parameterName)
					)
					->setParameter($parameterName, $this->parameter);

				break;

			case self::LESS_THAN:

				$queryBuilder
					->andWhere(
						$expr->lt($this->getField(), ':' . $parameterName)
					)
					->setParameter($parameterName, $this->parameter);

				break;

			case self::LESS_THAN_EQUALS:

				$queryBuilder
					->andWhere(
						$expr->lte($this->getField(), ':' . $parameterName)
					)
					->setParameter($parameterName, $this->parameter);

				break;
		}
	}
}
