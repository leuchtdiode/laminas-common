<?php
namespace Common\Dto;

use Common\Db\Entity;
use Common\Db\EntityRepository;
use Common\Db\Filter\Property;
use Common\Db\FilterChain;
use Common\Dto\Provide\HandleFilterParams;
use Common\Dto\Provide\HandleFilterResult;
use DateTime;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Psr\Container\ContainerInterface;
use Throwable;

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

		foreach ($params->getFilter() as $filterItem)
		{
			if ($filterItem->getType() === 'generic')
			{
				$value = $filterItem->getValue();

				$filterType = $value['filterType'];

				if ($filterType === 'equals')
				{
					$filterChain->addFilter(
						Property::filter(
							Property\EqualsParams::create()
								->setPropertyChain(
									Property\PropertyChain::buildFromString($filterItem->getProperty())
								)
								->setValues($value['filterValues'])
						)
					);
				}

				if ($filterType === 'not-equals')
				{
					$filterChain->addFilter(
						Property::filter(
							Property\EqualsParams::create()
								->setPropertyChain(
									Property\PropertyChain::buildFromString($filterItem->getProperty())
								)
								->setValues($value['filterValues'])
								->setNot(true)
						)
					);
				}

				if ($filterType === 'boolean')
				{
					$filterChain->addFilter(
						Property::filter(
							Property\BooleanParams::byValue($value['filterValue'])
								->setPropertyChain(
									Property\PropertyChain::buildFromString($filterItem->getProperty())
								)
						)
					);
				}

				if ($filterType === 'date')
				{
					$filterChain->addFilter(
						Property::filter(
							Property\DateParams::create($value['dateType'], new DateTime($value['date']))
								->setPropertyChain(
									Property\PropertyChain::buildFromString($filterItem->getProperty())
								)
						)
					);
				}

				if ($filterType === 'in')
				{
					$filterChain->addFilter(
						Property::filter(
							Property\InParams::create()
								->setValues($value['filterValues'])
								->setPropertyChain(
									Property\PropertyChain::buildFromString($filterItem->getProperty())
								)
						)
					);
				}

				// more generic filters here
			}
		}

		$result->setFilterChain($filterChain);

		return $result;
	}

	/**
	 * @throws Throwable
	 */
	public function byId($id, ?CreateOptions $createOptions = null): ?Dto
	{
		$repository = $this->getRepository();

		return ($entity = $repository->find($id))
			? $this->createDto($entity, $createOptions)
			: null;
	}

	/**
	 * @throws Throwable
	 */
	public function reload(Dto $dto, ?CreateOptions $createOptions = null): ?Dto
	{
		return $this->byId($dto->getId(), $createOptions);
	}

	/**
	 * @return Dto[]
	 * @throws Throwable
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
	 * @throws Throwable
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

	/**
	 * @throws Throwable
	 */
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
	 * @throws Throwable
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
