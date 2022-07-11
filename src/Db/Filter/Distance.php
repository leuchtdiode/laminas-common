<?php
namespace Common\Db\Filter;

use Common\Db\Filter;
use Doctrine\ORM\QueryBuilder;
use Exception;

class Distance implements Filter
{
	const TYPE_MIN = 'min';
	const TYPE_MAX = 'max';

	private string $type;
	private        $latitudeSource;
	private        $longitudeSource;
	private        $latitudeDestination;
	private        $longitudeDestination;
	private float  $kilometers;

	private function __construct(
		string $type,
		$latitudeSource,
		$longitudeSource,
		$latitudeDestination,
		$longitudeDestination,
		float $kilometers
	)
	{
		$this->type                 = $type;
		$this->latitudeSource       = $latitudeSource;
		$this->longitudeSource      = $longitudeSource;
		$this->latitudeDestination  = $latitudeDestination;
		$this->longitudeDestination = $longitudeDestination;
		$this->kilometers           = $kilometers;
	}

	public static function min(
		$latitudeSource,
		$longitudeSource,
		$latitudeDestination,
		$longitudeDestination,
		int $kilometers
	): self
	{
		return new self(
			self::TYPE_MIN,
			$latitudeSource,
			$longitudeSource,
			$latitudeDestination,
			$longitudeDestination,
			$kilometers
		);
	}

	public static function max(
		$latitudeSource,
		$longitudeSource,
		$latitudeDestination,
		$longitudeDestination,
		int $kilometers
	): self
	{
		return new self(
			self::TYPE_MAX,
			$latitudeSource,
			$longitudeSource,
			$latitudeDestination,
			$longitudeDestination,
			$kilometers
		);
	}

	/**
	 * @throws Exception
	 */
	public function addClause(QueryBuilder $queryBuilder): void
	{
		$expr = $queryBuilder->expr();

		$latitudeSourceParam       = uniqid('lat');
		$longitudeSourceParam      = uniqid('lon');
		$latitudeDestinationParam  = uniqid('lat');
		$longitudeDestinationParam = uniqid('lon');

		$distance = sprintf(
			'DISTANCE(:%s, :%s, :%s, :%s)',
			$latitudeSourceParam,
			$longitudeSourceParam,
			$latitudeDestinationParam,
			$longitudeDestinationParam
		);

		if ($this->type === self::TYPE_MIN)
		{
			$queryBuilder
				->andWhere(
					$expr->gte($distance, $this->kilometers)
				)
				->setParameter($latitudeSourceParam, $this->latitudeSource)
				->setParameter($longitudeSourceParam, $this->longitudeSource)
				->setParameter($latitudeDestinationParam, $this->latitudeDestination)
				->setParameter($longitudeDestinationParam, $this->longitudeDestination);

			return;
		}

		if ($this->type === self::TYPE_MAX)
		{
			$queryBuilder
				->andWhere(
					$expr->lte($distance, $this->kilometers)
				)
				->setParameter($latitudeSourceParam, $this->latitudeSource)
				->setParameter($longitudeSourceParam, $this->longitudeSource)
				->setParameter($latitudeDestinationParam, $this->latitudeDestination)
				->setParameter($longitudeDestinationParam, $this->longitudeDestination);

			return;
		}

		throw new Exception('Invalid type given');
	}
}