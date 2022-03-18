<?php
namespace Common\Db\Filter;

use DateTime;
use Common\Db\Filter;
use Doctrine\ORM\QueryBuilder;
use Exception;
use RuntimeException;

abstract class Date implements Filter
{
	const IS      = 'is';
	const IN_DAYS = 'in_days';
	const MAX     = 'max';
	const MIN     = 'min';
	const BEFORE  = 'before';
	const AFTER   = 'after';
	const MODULO  = 'modulo';

	protected DateTime $value;

	protected string $mode;

	abstract protected function getColumn(): string;

	private function __construct(
		DateTime $value,
		string $mode = self::IN_DAYS
	)
	{
		$this->value = $value;
		$this->mode  = $mode;
	}

	/**
	 * @throws Exception
	 */
	public static function inDays(int $days): static
	{
		$date = new DateTime();
		$date->modify(
			sprintf(
				'%s%d days',
				$days < 0
					? '-'
					: '+',
				abs($days)
			)
		);

		return new static($date, self::IN_DAYS);
	}

	public static function is(DateTime $date): static
	{
		return new static($date, self::IS);
	}

	public static function min(DateTime $date): static
	{
		return new static($date, self::MIN);
	}

	public static function max(DateTime $date): static
	{
		return new static($date, self::MAX);
	}

	public static function before(DateTime $date): static
	{
		return new static($date, self::BEFORE);
	}

	public static function after(DateTime $date): static
	{
		return new static($date, self::AFTER);
	}

	/**
	 * @throws Exception
	 */
	public static function modulo(int $days): static
	{
		$date = new DateTime();
		$date->modify(
			sprintf(
				'%s%d days',
				$days < 0
					? '-'
					: '+',
				abs($days)
			)
		);

		return new static($date, self::MODULO);
	}

	public function addClause(QueryBuilder $queryBuilder): void
	{
		$exp = $queryBuilder->expr();

		switch ($this->mode)
		{
			case self::IS:

				$queryBuilder->andWhere(
					$exp->eq(
						$this->getColumn(),
						$exp->literal($this->value->format('Y-m-d H:i:s'))
					)
				);

				break;

			case self::IN_DAYS:
				$queryBuilder->andWhere(
					$exp->eq(
						"DATE_FORMAT({$this->getColumn()}, '%Y-%m-%d')",
						$exp->literal($this->value->format('Y-m-d'))
					)
				);

				break;

			case self::MIN:
				$queryBuilder->andWhere(
					$exp->gte(
						$this->getColumn(),
						$exp->literal($this->value->format('Y-m-d H:i:s'))
					)
				);

				break;

			case self::MAX:
				$queryBuilder->andWhere(
					$exp->lte(
						$this->getColumn(),
						$exp->literal($this->value->format('Y-m-d H:i:s'))
					)
				);

				break;

			case self::BEFORE:
				$queryBuilder->andWhere(
					$exp->lt(
						$this->getColumn(),
						$exp->literal($this->value->format('Y-m-d H:i:s'))
					)
				);

				break;

			case self::AFTER:
				$queryBuilder->andWhere(
					$exp->gt(
						$this->getColumn(),
						$exp->literal($this->value->format('Y-m-d H:i:s'))
					)
				);

				break;

			case self::MODULO:
				$queryBuilder->andWhere(
					$exp->eq(
						"DATE_FORMAT({$this->getColumn()}, '%m-%d')",
						$exp->literal($this->value->format('m-d'))
					)
				);

				break;

			default:
				throw new RuntimeException("invalid mode in string filter");
		}

	}
}
