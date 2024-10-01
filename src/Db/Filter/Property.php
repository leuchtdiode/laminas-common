<?php
declare(strict_types=1);

namespace Common\Db\Filter;

use Common\Db\Filter;
use Common\Db\Filter\Property\BaseParams;
use Doctrine\ORM\Query\Expr\Orx;
use Doctrine\ORM\QueryBuilder;

class Property implements Filter
{
	public function __construct(
		private readonly BaseParams $params
	)
	{
	}

	public static function filter(BaseParams $params)
	{
		return new static($params);
	}

	public function addClause(QueryBuilder $queryBuilder): void
	{
		$propertyChain = $this->params->getPropertyChain();

		$orX = new Orx();

		$expr = $queryBuilder->expr();

		$subAlias = uniqid('s');

		$rootEntities = $queryBuilder->getRootEntities();
		$rootEntity   = reset($rootEntities);

		$subQb = $queryBuilder
			->getEntityManager()
			->createQueryBuilder()
			->select($subAlias)
			->from($rootEntity, $subAlias)
			->andWhere(
				$expr->eq('t', $subAlias)
			);

		$propertyChainProperties = $propertyChain->getProperties();

		$propertyNameToFilter = null;

		foreach ($propertyChainProperties as $propIndex => $property)
		{
			$isLast = $propIndex === count($propertyChainProperties) - 1;

			if (!$isLast)
			{
				$subQb->leftJoin($subAlias . '.' . $property->getName(), $subAlias = uniqid('s'));
			}
			else
			{
				$propertyNameToFilter = $property->getName();
			}
		}

		if ($this->params instanceof Filter\Property\InParams)
		{
			$itemOr = new Orx();

			foreach ($this->params->getValues() as $value)
			{
				$valueParam = uniqid('vp');

				$itemOr->add(
					sprintf(
						'FIND_IN_SET(:%s, %s.%s) > 0',
						$valueParam,
						$subAlias,
						$propertyNameToFilter
					)
				);

				$queryBuilder->setParameter($valueParam, $value);
			}

			$orX->add($itemOr);
		}

		if ($this->params instanceof Filter\Property\EqualsParams)
		{
			$valuesParam = uniqid('vp');

			$orX->add(
				$expr->in($subAlias . '.' . $propertyNameToFilter, ':' . $valuesParam)
			);

			$queryBuilder->setParameter($valuesParam, $this->params->getValues());
		}

		if ($this->params instanceof Filter\Property\BooleanParams)
		{
			$orX->add(
				$this->params->getComparison($queryBuilder, $subAlias . '.' . $propertyNameToFilter)
			);
		}

		if ($this->params instanceof Filter\Property\DateParams)
		{
			$orX->add(
				$this->params->getComparison($queryBuilder, $subAlias . '.' . $propertyNameToFilter)
			);
		}

		$subQb->andWhere($orX);

		$queryBuilder->andWhere(
			$expr->exists($subQb->getDQL())
		);
	}
}
