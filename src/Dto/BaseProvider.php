<?php
namespace Common\Dto;

use Common\Db\EntityRepository;
use Common\Db\FilterChain;
use Common\Db\Entity;
use Common\Dto\Provide\HandleFilterParams;
use Common\Dto\Provide\HandleFilterResult;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Psr\Container\ContainerInterface;

abstract class BaseProvider
{
	abstract protected function getKey(): string;

	public function __construct(
		private readonly ContainerInterface $container,
		private readonly KeyConfig $keyConfig,
		private readonly DefaultMapper $defaultMapper
	)
	{
	}

	protected function getRepository(): EntityRepository
	{
		return $this->container->get($this->keyConfig->getDbNamespace($this->getKey()) . '\Repository');
	}

	public function handleFilter(HandleFilterParams $params): HandleFilterResult
	{
		$result = new HandleFilterResult();

		$filterChain = $params->getFilterChain();

		// TODO do generic filters here

		$result->setFilterChain($filterChain);

		return $result;
	}

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
					$filterData->getLimit(),
					$filterData->isDistinct()
				),
			$createOptions
		);
	}

	/**
	 * @return mixed[]
	 */
	public function filterAndReturnIds(FilterData $filterData): array
	{
		return $this
			->getRepository()
			->filterAndReturnIds(
				$filterData->getFilterChain(),
				$filterData->getOrderChain(),
				$filterData->getOffset(),
				$filterData->getLimit(),
				$filterData->isDistinct()
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
		return $this->defaultMapper->map(
			DefaultMapParams::create()
				->setEntity($entity)
				->setCreateOptions($createOptions)
		);
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
