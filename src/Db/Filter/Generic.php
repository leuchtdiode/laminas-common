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
			$conditions[] = $this->getCondition($queryBuilder, $exp, $value);
		}

		$queryBuilder->andWhere(new Andx($conditions));
	}

	private function getCondition(
		QueryBuilder $queryBuilder,
		Expr $exp,
		string $value
	): Orx
	{
		$conditions = [];

		foreach ($this->getColumns() as $column => $mode)
		{
			$param = uniqid('p');

			switch ($mode)
			{
				case self::EQ:
					$queryBuilder->setParameter($param, $value);
					$condition = $exp->eq($column, ':' . $param);
					break;

				case self::LIKE:
					$queryBuilder->setParameter($param, "%$value%");
					$condition = $exp->like($column, ':' . $param);
					break;

				case self::STARTS_WITH:
					$queryBuilder->setParameter($param, "$value%");
					$condition = $exp->like($column, ':' . $param);
					break;

				case self::ENDS_WITH:
					$queryBuilder->setParameter($param, "%$value");
					$condition = $exp->like($column, ':' . $param);
					break;

				default:
					throw new RuntimeException("invalid mode in string filter");
			}

			$conditions[] = $condition;
		}

		return new Orx($conditions);
	}
}
