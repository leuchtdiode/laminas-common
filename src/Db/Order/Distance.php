<?php
namespace Common\Db\Order;

use Common\Db\Order;
use Doctrine\ORM\QueryBuilder;

abstract class Distance implements Order
{
	private float $latitude;

	private float $longitude;

	private string $direction;

	abstract protected function getAlias(): string;

	private function __construct(float $latitude, float $longitude, string $direction)
	{
		$this->latitude  = $latitude;
		$this->longitude = $longitude;
		$this->direction = $direction;
	}

	public static function nearest(float $latitude, float $longitude): self
	{
		return new static($latitude, $longitude, 'ASC');
	}

	public static function widest(float $latitude, float $longitude): self
	{
		return new static($latitude, $longitude, 'DESC');
	}

	public function addOrder(QueryBuilder $queryBuilder): void
	{
		$alias = $this->getAlias();

		$distanceColumn = uniqid('d');

		$queryBuilder
			->addSelect(
				sprintf(
					'DISTANCE(:%s, :%s, %s.latitude, %s.longitude) AS HIDDEN %s',
					$latitudeParam = uniqid('lat'),
					$longitudeParam = uniqid('lon'),
					$alias,
					$alias,
					$distanceColumn
				)
			)
			->setParameter($latitudeParam, $this->latitude)
			->setParameter($longitudeParam, $this->longitude)
			->addOrderBy($distanceColumn, $this->direction);
	}
}