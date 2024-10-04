<?php
declare(strict_types=1);

namespace Common\Db\Filter\Property;

use DateTime;
use Doctrine\ORM\Query\Expr\Comparison;
use Doctrine\ORM\QueryBuilder;
use RuntimeException;

class DateParams extends BaseParams
{
	public const string TYPE__IS      = 'is';
	public const string TYPE__IN_DAYS = 'in_days';
	public const string TYPE__MAX     = 'max';
	public const string TYPE__MIN     = 'min';
	public const string TYPE__BEFORE  = 'before';
	public const string TYPE__AFTER   = 'after';

	public function __construct(
		private readonly string $type,
		private readonly DateTime $dateTime
	)
	{

	}

	public static function create(string $type, DateTime $dateTime): static
	{
		return new static($type, $dateTime);
	}

	public static function min(DateTime $dateTime): static
	{
		return new static(self::TYPE__MIN, $dateTime);
	}

	public static function max(DateTime $dateTime): static
	{
		return new static(self::TYPE__MAX, $dateTime);
	}

	public static function is(DateTime $dateTime): static
	{
		return new static(self::TYPE__IS, $dateTime);
	}

	public static function inDays(DateTime $dateTime): static
	{
		return new static(self::TYPE__IN_DAYS, $dateTime);
	}

	public static function before(DateTime $dateTime): static
	{
		return new static(self::TYPE__BEFORE, $dateTime);
	}

	public static function after(DateTime $dateTime): static
	{
		return new static(self::TYPE__AFTER, $dateTime);
	}

	public function getComparison(QueryBuilder $queryBuilder, string $field): Comparison
	{
		$exp = $queryBuilder->expr();

		$valueParam = uniqid('vp');

		switch ($this->type)
		{
			case self::TYPE__IS:

				$value = $this->dateTime->format('Y-m-d H:i:s');

				$composite = $exp->eq($field, ':' . $valueParam);

				break;

			case self::TYPE__IN_DAYS:

				$value = $this->dateTime->format('Y-m-d');

				$composite = $exp->eq("DATE_FORMAT({$field}, '%Y-%m-%d')", ':' . $valueParam);

				break;

			case self::TYPE__MIN:

				$value = $this->dateTime->format('Y-m-d H:i:s');

				$composite = $exp->gte($field, ':' . $valueParam);

				break;

			case self::TYPE__MAX:

				$value = $this->dateTime->format('Y-m-d H:i:s');

				$composite = $exp->lte($field, ':' . $valueParam);

				break;

			case self::TYPE__BEFORE:

				$value = $this->dateTime->format('Y-m-d H:i:s');

				$composite = $exp->lt($field, ':' . $valueParam);

				break;

			case self::TYPE__AFTER:

				$value = $this->dateTime->format('Y-m-d H:i:s');

				$composite = $exp->gt($field, ':' . $valueParam);

				break;

			default:
				throw new RuntimeException("invalid mode in string filter");
		}

		$queryBuilder->setParameter($valueParam, $value);

		return $composite;
	}

	public function getType(): string
	{
		return $this->type;
	}
}
