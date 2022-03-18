<?php
namespace Common\Dto;

use Common\Db\EntityRepository;
use Common\Db\FilterChain;
use Common\Db\Entity;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;

abstract class Provider
{
	abstract protected function getRepository(): EntityRepository;

	abstract protected function getDtoMapping(): Mapping;

	public function byId($id, ?CreateOptions $createOptions = null): ?Dto
	{
		$repository = $this->getRepository();

		return ($entity = $repository->find($id))
			? $this->createDto($entity, $createOptions)
			: null;
	}

	/**
	 * @return Dto[]
	 */
	public function all(?CreateOptions $createOptions = null): array
	{
		return $this->createDtos(
			$this
				->getRepository()
				->findAll(),
			$createOptions
		);
	}

	/**
	 * @return Dto[]
	 */
	public function filter(FilterData $filterData, ?CreateOptions $createOptions = null): array
	{
		return $this->createDtos(
			$this
				->getRepository()
				->filter(
					$filterData->getFilterChain(),
					$filterData->getOrderChain(),
					$filterData->getOffset(),
					$filterData->getLimit()
				),
			$createOptions
		);
	}

	/**
	 * @param FilterChain $filterChain
	 * @return int
	 * @throws NonUniqueResultException|NoResultException
	 */
	public function countWithFilter(FilterChain $filterChain): int
	{
		return $this
			->getRepository()
			->countWithFilter($filterChain);
	}

	protected function createDto(Entity $entity, ?CreateOptions $createOptions = null): Dto
	{
		return $this
			->getDtoMapping()
			->createSingle($entity, $createOptions);
	}

	/**
	 * @param Entity[] $entities
	 * @return Dto[]
	 */
	protected function createDtos(array $entities, ?CreateOptions $createOptions = null): array
	{
		return array_map(
			function ($entity) use ($createOptions)
			{
				return $this->createDto($entity, $createOptions);
			},
			$entities
		);
	}
}