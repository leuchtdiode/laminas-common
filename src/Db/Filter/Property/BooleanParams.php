<?php
declare(strict_types=1);

namespace Common\Db\Filter\Property;

use Doctrine\ORM\Query\Expr\Comparison;
use Doctrine\ORM\QueryBuilder;

class BooleanParams extends BaseParams
{
	private bool $nullMeansNo = false;

	private function __construct(
		private readonly bool $yesOrNo
	)
	{
	}

	public static function yes(): static
	{
		return new static(true);
	}

	public static function no(): static
	{
		return new static(false);
	}

	public function getComparison(QueryBuilder $queryBuilder, string $field): Comparison
	{
		$expr = $queryBuilder->expr();

		$comparison = null;

		if ($this->yesOrNo)
		{
			$comparison = $expr->eq($field, true);
		}
		else
		{
			if ($this->nullMeansNo)
			{
				$comparison = $expr->orX(
					$expr->eq($field, false),
					$expr->isNull($field),
				);
			}
			else
			{
				$comparison = $expr->eq($field, false);
			}
		}

		return $comparison;
	}

	public function isYesOrNo(): bool
	{
		return $this->yesOrNo;
	}

	public function isNullMeansNo(): bool
	{
		return $this->nullMeansNo;
	}

	public function setNullMeansNo(bool $nullMeansNo): BooleanParams
	{
		$this->nullMeansNo = $nullMeansNo;
		return $this;
	}
}
