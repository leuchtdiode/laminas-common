<?php
declare(strict_types=1);

namespace Common\Db\Filter\Property;

use Doctrine\ORM\QueryBuilder;

class HandleParams
{
	private QueryBuilder $queryBuilder;
	private BaseParams   $params;
	private string       $subAlias;
	private string       $propertyName;

	public static function create(): static
	{
		return new static();
	}

	public function getQueryBuilder(): QueryBuilder
	{
		return $this->queryBuilder;
	}

	public function setQueryBuilder(QueryBuilder $queryBuilder): HandleParams
	{
		$this->queryBuilder = $queryBuilder;
		return $this;
	}

	public function getParams(): BaseParams
	{
		return $this->params;
	}

	public function setParams(BaseParams $params): HandleParams
	{
		$this->params = $params;
		return $this;
	}

	public function getSubAlias(): string
	{
		return $this->subAlias;
	}

	public function setSubAlias(string $subAlias): HandleParams
	{
		$this->subAlias = $subAlias;
		return $this;
	}

	public function getPropertyName(): string
	{
		return $this->propertyName;
	}

	public function setPropertyName(string $propertyName): HandleParams
	{
		$this->propertyName = $propertyName;
		return $this;
	}
}
