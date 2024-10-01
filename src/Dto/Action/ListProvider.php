<?php
declare(strict_types=1);

namespace Common\Dto\Action;

use Common\Action\Meta;
use Common\Db\FilterChain;
use Common\Db\Order\Property;
use Common\Db\OrderChain;
use Common\Dto\BaseProvider;
use Common\Dto\CreateOptions\Generic;
use Common\Dto\CreateOptions\PropertyToIgnore;
use Common\Dto\CreateOptions\PropertyToLoad;
use Common\Dto\FilterData;
use Common\Dto\KeyConfig;
use Common\Dto\Provide\FilterItem;
use Common\Dto\Provide\HandleFilterParams;
use Exception;
use Psr\Container\ContainerInterface;
use ReflectionClass;
use Throwable;

class ListProvider
{
	public function __construct(
		private readonly ContainerInterface $container,
		private readonly KeyConfig $keyConfig
	)
	{
	}

	/**
	 * @throws Throwable
	 */
	public function get(ListParams $params): ListResult
	{
		$listResult = new ListResult();

		$getParams = $params->getGetParams();

		if (!($dtoNamespace = $this->keyConfig->getNamespace($params->getDtoKey())))
		{
			throw new Exception('Could not find namespace');
		}

		$offset = (int)($getParams['offset'] ?? 0);
		$limit  = (int)($getParams['limit'] ?? 100);

		$errors = [];

		if ($offset < 0)
		{
			$errors[] = ParamInvalid::create('Offset invalid');
		}

		if ($limit < 0)
		{
			$errors[] = ParamInvalid::create('Limit invalid');
		}

		if ($errors)
		{
			$listResult->setErrors($errors);
			return $listResult;
		}

		$propertiesToLoad = [];

		foreach (($getParams['propertiesToLoad'] ?? []) as $item)
		{
			$propertiesToLoad[] = PropertyToLoad::create()
				->setDtoKey($item['dtoKey'])
				->setProperty($item['property']);
		}

		$propertiesToIgnore = [];

		foreach (($getParams['propertiesToIgnore'] ?? []) as $item)
		{
			$propertiesToIgnore[] = PropertyToIgnore::create()
				->setDtoKey($item['dtoKey'])
				->setProperty($item['property']);
		}

		/**
		 * @var BaseProvider $provider
		 */
		$provider = $this->container->get($dtoNamespace . '\Provider');

		$filterChain = FilterChain::create();

		$filterChain = $provider
			->handleFilter(
				HandleFilterParams::create()
					->setFilter(
						array_map(
							fn(array $item) => FilterItem::fromArray($item),
							$getParams['filter'] ?? []
						)
					)
					->setFilterChain($filterChain)
			)
			->getFilterChain();

		$orderChain = OrderChain::create();

		if (($order = $getParams['order'] ?? []))
		{
			$orderChain = $this->buildOrder($order, $this->keyConfig->getDbNamespace($params->getDtoKey()));
		}

		try
		{
			$listResult
				->setItems(
					$items = $provider->filter(
						FilterData::create()
							->setFilterChain($filterChain)
							->setOrderChain($orderChain)
							->setOffset($offset)
							->setLimit($limit),
						Generic::create()
							->setPropertiesToLoad($propertiesToLoad)
							->setPropertiesToIgnore($propertiesToIgnore)
					)
				)
				->setMeta(
					Meta::create()
						->setOffset($offset)
						->setTotal(
							$provider->countWithFilter($filterChain)
						)
						->setCount(count($items))
				);

			return $listResult;
		}
		catch (Throwable $t)
		{
			throw $t;
		}
	}

	private function buildOrder(array $order, string $dbNamespace): OrderChain
	{
		$orderChain = OrderChain::create();

		foreach ($order as $orderItem)
		{
			$property  = $orderItem['property'] ?? null;
			$direction = strtolower($orderItem['direction'] ?? '');

			if (!$property || !in_array($direction, [ 'asc', 'desc' ]))
			{
				continue;
			}

			$reflectionClass = new ReflectionClass($dbNamespace . '\Entity');

			$property = $reflectionClass->getProperty($property);

			$orderChain->addOrder(
				Property::withDirection($direction)
					->setProperty($property->getName())
			);
		}

		return $orderChain;
	}
}
