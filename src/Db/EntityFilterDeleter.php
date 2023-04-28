<?php
namespace Common\Db;

use Doctrine\ORM\EntityManager;
use Throwable;

class EntityFilterDeleter
{
	protected EntityManager $entityManager;

	public function __construct(EntityManager $entityManager)
	{
		$this->entityManager = $entityManager;
	}

	/**
	 * @throws Throwable
	 */
	public function filterDelete(string $entityClass, FilterChain $filterChain): int
	{
		$queryBuilder = $this->entityManager
			->getRepository($entityClass)
			->createQueryBuilder('t');

		foreach ($filterChain->getFilters() as $filter)
		{
			$filter->addClause($queryBuilder);
		}

		return $queryBuilder
			->delete()
			->getQuery()
			->execute();
	}
}