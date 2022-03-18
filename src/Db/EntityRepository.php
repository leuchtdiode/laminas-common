<?php
namespace Common\Db;

use Doctrine\ORM\EntityRepository as DoctrineEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;

class EntityRepository extends DoctrineEntityRepository
{
	/**
	 * @return Entity[]
	 */
	public function filter(
		FilterChain $filterChain,
		OrderChain $orderChain = null,
		int $offset = 0,
		int $limit = PHP_INT_MAX,
		bool $distinct = false
	): array
	{
		$queryBuilder = $this->createQueryBuilder('t');

		if ($distinct)
		{
			$queryBuilder->distinct();
		}

		foreach ($filterChain->getFilters() as $filter)
		{
			$filter->addClause($queryBuilder);
		}

		if ($orderChain)
		{
			foreach ($orderChain->getOrders() as $order)
			{
				$order->addOrder($queryBuilder);
			}
		}

		$queryBuilder->setFirstResult($offset);
		$queryBuilder->setMaxResults($limit);

		return $queryBuilder
			->getQuery()
			->getResult();
	}

	/**
	 * @throws NonUniqueResultException
	 * @throws NoResultException
	 */
	public function countWithFilter(FilterChain $filterChain = null, bool $distinct = false): int
	{
		$identifiers = $this
			->getClassMetadata()
			->getIdentifier();

		$queryBuilder = $this
			->createQueryBuilder('t')
			->select('COUNT(' . ($distinct
					? 'DISTINCT'
					: '') . ' t.' . $identifiers[0] . ')');

		if ($filterChain)
		{
			foreach ($filterChain->getFilters() as $filter)
			{
				$filter->addClause($queryBuilder);
			}
		}

		return $queryBuilder
			->getQuery()
			->getSingleScalarResult();

	}
}
