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
	private float  $latitude;
	private float  $longitude;
	private float  $kilometers;

	private function __construct(string $type, float $latitude, float $longitude, int $kilometers)
	{
		$this->type       = $type;
		$this->latitude   = $latitude;
		$this->longitude  = $longitude;
		$this->kilometers = $kilometers;
	}

	public static function min(float $latitude, float $longitude, int $kilometers): self
	{
		return new self(self::TYPE_MIN, $latitude, $longitude, $kilometers);
	}

	public static function max(float $latitude, float $longitude, int $kilometers): self
	{
		return new self(self::TYPE_MAX, $latitude, $longitude, $kilometers);
	}

	/**
	 * @throws Exception
	 */
	public function addClause(QueryBuilder $queryBuilder): void
	{
		$expr = $queryBuilder->expr();

		$latitudeParam  = uniqid('lat');
		$longitudeParam = uniqid('lon');

		if ($this->type === self::TYPE_MIN)
		{
			$queryBuilder
				->andWhere(
					$expr->gte(
						'DISTANCE(t.latitude, t.longitude, :' . $latitudeParam . ', :' . $longitudeParam . ')',
						$this->kilometers
					)
				)
				->setParameter($latitudeParam, $this->latitude)
				->setParameter($longitudeParam, $this->longitude);

			return;
		}

		if ($this->type === self::TYPE_MAX)
		{
			$queryBuilder
				->andWhere(
					$expr->lte(
						'DISTANCE(t.latitude, t.longitude, :' . $latitudeParam . ', :' . $longitudeParam . ')',
						$this->kilometers
					)
				)
				->setParameter($latitudeParam, $this->latitude)
				->setParameter($longitudeParam, $this->longitude);

			return;
		}

		throw new Exception('Invalid type given');
	}
}