<?php
namespace Common\Db\Filter;

use Common\Db\Filter;
use Doctrine\ORM\Query\Expr;
use Doctrine\ORM\Query\Expr\Andx;
use Doctrine\ORM\Query\Expr\Orx;
use Doctrine\ORM\QueryBuilder;
use RuntimeException;

abstract class Generic implements Filter
{
	const EQ          = 'eq';
	const LIKE        = 'like';
	const STARTS_WITH = 'starts_with';
	const ENDS_WITH   = 'ends_with';

	private string $value;

	abstract protected function getColumns(): array;

	private function __construct(string $value)
	{
		$this->value = $value;
	}

	public static function search(string $value): static
	{
		return new static($value);
	}

	/**
	 * @param QueryBuilder $queryBuilder
	 */
	public function addClause(QueryBuilder $queryBuilder): void
	{
		$exp = $queryBuilder->expr();

		$conditions = [];

		$values = preg_split('!\s!', $this->value);

		foreach ($values as $value)
		{
			$conditions[] = $this->getCondition($exp, $value);
		}

		$queryBuilder->andWhere(new Andx($conditions));
	}

	private function getCondition(
		Expr $exp,
		string $value
	): Orx
	{
		$conditions = [];

		foreach ($this->getColumns() as $column => $mode)
		{
			$condition = match ($mode)
			{
				self::EQ => $exp->eq($column, $exp->literal($value)),
				self::LIKE => $exp->like($column, $exp->literal("%{$value}%")),
				self::STARTS_WITH => $exp->like($column, $exp->literal("{$value}%")),
				self::ENDS_WITH => $exp->like($column, $exp->literal("%{$value}")),
				default => throw new RuntimeException("invalid mode in string filter"),
			};

			$conditions[] = $condition;
		}

		return new Orx($conditions);
	}
}
