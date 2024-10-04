<?php
declare(strict_types=1);

namespace Common\Db\Filter;

use Common\Db\Filter;
use Common\Db\Filter\Property\BaseParams;
use Common\Db\Filter\Property\HandleParams;
use Doctrine\ORM\Query\Expr\Andx;
use Doctrine\ORM\Query\Expr\Orx;
use Doctrine\ORM\QueryBuilder;
use Exception;
use Throwable;

class Property implements Filter
{
	public function __construct(
		private readonly BaseParams $params
	)
	{
	}

	public static function filter(BaseParams $params): static
	{
		return new static($params);
	}

	/**
	 * @throws Throwable
	 */
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

			if (($params = $property->getParams()))
			{
				$andx = new Andx();

				foreach ($params as $theParams)
				{
					$andx->add(
						$this->handleParams(
							Filter\Property\HandleParams::create()
								->setQueryBuilder($queryBuilder)
								->setParams($theParams)
								->setSubAlias($subAlias)
								->setPropertyName($theParams->getProperty())
						)
					);
				}

				$subQb->andWhere($andx);
			}
		}

		$orX->add(
			$this->handleParams(
				Filter\Property\HandleParams::create()
					->setQueryBuilder($queryBuilder)
					->setParams($this->params)
					->setSubAlias($subAlias)
					->setPropertyName($propertyNameToFilter)
			)
		);

		$subQb->andWhere($orX);

		$queryBuilder->andWhere(
			$expr->exists($subQb->getDQL())
		);
	}

	/**
	 * @throws Throwable
	 */
	private function handleParams(HandleParams $handleParams): mixed
	{
		$queryBuilder         = $handleParams->getQueryBuilder();
		$expr                 = $queryBuilder->expr();
		$params               = $handleParams->getParams();
		$propertyNameToFilter = $handleParams->getPropertyName();
		$subAlias             = $handleParams->getSubAlias();

		if ($params instanceof Filter\Property\InParams)
		{
			$itemOr = new Orx();

			foreach ($params->getValues() as $value)
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

			return $itemOr;
		}

		if ($params instanceof Filter\Property\EqualsParams)
		{
			$valuesParam = uniqid('vp');

			$queryBuilder->setParameter($valuesParam, $params->getValues());

			return $expr->in($subAlias . '.' . $propertyNameToFilter, ':' . $valuesParam);
		}

		if ($params instanceof Filter\Property\NullParams)
		{
			return $params->getComparison($queryBuilder, $subAlias . '.' . $propertyNameToFilter);
		}

		if ($params instanceof Filter\Property\BooleanParams)
		{
			return $params->getComparison($queryBuilder, $subAlias . '.' . $propertyNameToFilter);
		}

		if ($params instanceof Filter\Property\DateParams)
		{
			return $params->getComparison($queryBuilder, $subAlias . '.' . $propertyNameToFilter);
		}

		throw new Exception('Could not handle params');
	}
}
