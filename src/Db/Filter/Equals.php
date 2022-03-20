<?php
namespace Common\Db\Filter;

use Common\Db\Filter;
use Doctrine\ORM\QueryBuilder;

abstract class Equals implements Filter
{
	const VALUE     = 'value';
	const NOT_VALUE = 'notValue';
	const NULL      = 'null';
	const NOT_NULL  = 'notNull';
	const IN        = 'in';
	const NOT_IN    = 'notIn';

	private string $type;

	private mixed $parameter = null;

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

	public static function is(mixed $parameter): static
	{
		return new static(self::VALUE, $parameter);
	}

	public static function isNot(mixed $parameter): static
	{
		return new static(self::NOT_VALUE, $parameter);
	}

	public static function in(array $parameter): static
	{
		return new static(self::IN, $parameter);
	}

	public static function notIn(array $parameter): static
	{
		return new static(self::NOT_IN, $parameter);
	}

	public static function isNull(): static
	{
		return new static(self::NULL);
	}

	public static function isNotNull(): static
	{
		return new static(self::NOT_NULL);
	}

	public function addClause(QueryBuilder $queryBuilder): void
	{
		// parameter name is optional now for child, uses randomized string by default
		$parameterName = $this->getParameterName() ?? uniqid('p');

		switch ($this->type)
		{
			case self::VALUE:

				$queryBuilder
					->andWhere(
						$queryBuilder
							->expr()
							->eq($this->getField(), ':' . $parameterName)
					)
					->setParameter($parameterName, $this->parameter);

				break;

			case self::NOT_VALUE:

				$queryBuilder
					->andWhere(
						$queryBuilder
							->expr()
							->neq($this->getField(), ':' . $parameterName)
					)
					->setParameter($parameterName, $this->parameter);

				break;

			case self::IN:

				$queryBuilder
					->andWhere(
						$queryBuilder
							->expr()
							->in($this->getField(), ':' . $parameterName)
					)
					->setParameter($parameterName, $this->parameter);

				break;

			case self::NOT_IN:

				$queryBuilder
					->andWhere(
						$queryBuilder
							->expr()
							->notIn($this->getField(), ':' . $parameterName)
					)
					->setParameter($parameterName, $this->parameter);

				break;

			case self::NULL:

				$queryBuilder
					->andWhere(
						$queryBuilder
							->expr()
							->isNull($this->getField())
					);

				break;

			case self::NOT_NULL:

				$queryBuilder
					->andWhere(
						$queryBuilder
							->expr()
							->isNotNull($this->getField())
					);

				break;
		}
	}
}
