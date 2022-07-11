<?php
namespace Common\Db\Filter;

use Common\Db\Filter;
use Doctrine\ORM\QueryBuilder;
use Exception;

class Distance implements Filter
{
	const TYPE_MIN = 'min';
	const TYPE_MAX = 'max';

	private Distance\FilterParams $params;

	private function __construct(Distance\FilterParams $params)
	{
		$this->params = $params;
	}

	public static function filter(Distance\FilterParams $params): Distance
	{
		return new static($params);
	}

	/**
	 * @throws Exception
	 */
	public function addClause(QueryBuilder $queryBuilder): void
	{
		$expr = $queryBuilder->expr();

		$type = $this->params->getType();

		if ($type !== self::TYPE_MIN && $type !== self::TYPE_MAX)
		{
			throw new Exception('Invalid type given');
		}

		$sourceLatitude       = $this->handleColumnOrValue($queryBuilder, $this->params->getSourceLatitude());
		$sourceLongitude      = $this->handleColumnOrValue($queryBuilder, $this->params->getSourceLongitude());
		$destinationLatitude  = $this->handleColumnOrValue($queryBuilder, $this->params->getDestinationLatitude());
		$destinationLongitude = $this->handleColumnOrValue($queryBuilder, $this->params->getDestinationLongitude());

		$distance = sprintf(
			'DISTANCE(%s, %s, %s, %s)',
			$sourceLatitude,
			$sourceLongitude,
			$destinationLatitude,
			$destinationLongitude
		);

		if ($type === self::TYPE_MIN)
		{
			$queryBuilder
				->andWhere(
					$expr->gte($distance, $this->params->getKilometers())
				);
		}

		if ($type === self::TYPE_MAX)
		{
			$queryBuilder
				->andWhere(
					$expr->lte($distance, $this->params->getKilometers())
				);
		}
	}

	private function handleColumnOrValue(QueryBuilder $queryBuilder, Filter\Distance\ColumnOrValue $columnOrValue)
	{
		$sourceLatitude = $columnOrValue->getColumn();

		if (!$sourceLatitude)
		{
			$sourceLatitude = ':' . ($sourceLatitudeParam = uniqid('lat'));

			$queryBuilder->setParameter(
				$sourceLatitudeParam,
				$columnOrValue->getValue()
			);
		}

		return $sourceLatitude;
	}
}