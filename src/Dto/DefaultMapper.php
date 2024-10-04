<?php
declare(strict_types=1);

namespace Common\Dto;

use Common\Db\Entity as CommonDbEntity;
use Common\Dto\CreateOptions\Generic;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Util\ClassUtils;
use Exception;
use Psr\Container\ContainerInterface;
use ReflectionClass;
use Throwable;

class DefaultMapper
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
	public function map(DefaultMapParams $params): ?Dto
	{
		$entity        = $params->getEntity();
		$level         = $params->getLevel();
		$createOptions = $params->getCreateOptions();

		$realClass = ClassUtils::getRealClass(get_class($entity));

		// attributes not showing up in real class
		$reflectionClass = new ReflectionClass($realClass);

		$entityConfigAttribute = $reflectionClass->getAttributes(EntityConfig::class)[0] ?? null;

		if (!$entityConfigAttribute)
		{
			return null;
		}

		$dtoKey = $entityConfigAttribute->getArguments()['dtoKey'];

		$dtoClass = $this->keyConfig->getNamespace($dtoKey) . '\Dto';

		$mappingConfig = (new ReflectionClass($dtoClass))->getAttributes(MappingConfig::class)[0] ?? null;

		$propertiesToIgnore = [];
		$forceProperties    = [];

		if ($mappingConfig)
		{
			$propertiesToIgnore = $mappingConfig->getArguments()['propertiesToIgnoreByDefault'] ?? [];
			$forceProperties    = $mappingConfig->getArguments()['forceProperties'] ?? [];
		}

		if ($createOptions instanceof Generic && ($propertiesToLoad = $createOptions->getPropertiesToLoad()))
		{
			foreach ($propertiesToLoad as $propertyToLoad)
			{
				if ($propertyToLoad->getDtoKey() === $dtoKey)
				{
					$forceProperties[] = $propertyToLoad->getProperty();
				}
			}
		}

		if (
			$createOptions instanceof Generic
			&& ($propertiesToIgnoreFromCreateOptions = $createOptions->getPropertiesToIgnore())
		)
		{
			foreach ($propertiesToIgnoreFromCreateOptions as $propertyToIgnore)
			{
				if ($propertyToIgnore->getDtoKey() === $dtoKey)
				{
					$propertiesToIgnore[] = $propertyToIgnore->getProperty();
				}
			}
		}

		$data = [];

		foreach ($reflectionClass->getProperties() as $property)
		{
			if (
				in_array($property->getName(), $propertiesToIgnore)
				&& !in_array($property->getName(), $forceProperties)
			)
			{
				continue;
			}

			$getter = $this->findGetter($entity, $property->getName());

			$value = $entity->{$getter}();

			if (is_object($value))
			{
				if (method_exists($value, '__toString'))
				{
					$data[$property->getName()] = $value->__toString();
				}

				if ($params->isResolveRelations() || in_array($property->getName(), $forceProperties))
				{
					$resolveRelations = $level <= 2; // resolve max 2 levels down by default

					if ($value instanceof CommonDbEntity)
					{
						$data[$property->getName()] = $this->map(
							DefaultMapParams::create()
								->setEntity($value)
								->setLevel($level + 1)
								->setCreateOptions($createOptions)
								->setResolveRelations($resolveRelations)
						);
					}

					if ($value instanceof Collection)
					{
						$data[$property->getName()] = array_map(
							function ($item) use ($level, $resolveRelations, $createOptions)
							{
								return $this->map(
									DefaultMapParams::create()
										->setEntity($item)
										->setLevel($level + 1)
										->setCreateOptions($createOptions)
										->setResolveRelations($resolveRelations)
								);
							},
							$value->toArray()
						);
					}
				}
			}
			else
			{
				$data[$property->getName()] = $value;
			}
		}

		$dataManipulatorClass = $mappingConfig?->getArguments()['dataManipulator'] ?? null;

		if ($dataManipulatorClass)
		{
			/**
			 * @var Mapping\DataManipulator $dataManipulator
			 */
			$dataManipulator = $this->container->get($dataManipulatorClass);

			$data = $dataManipulator
				->manipulate(
					Mapping\DataManipulationParams::create()
						->setData($data)
				)
				->getData();
		}

		return new $dtoClass($dtoKey, $data);
	}

	/**
	 * @throws Throwable
	 */
	private function findGetter(CommonDbEntity $entity, string $propertyName): string
	{
		foreach ([ 'get', 'is' ] as $prefix)
		{
			$getter = $prefix . ucfirst($propertyName);

			if (method_exists($entity, $getter))
			{
				return $getter;
			}
		}

		throw new Exception('Could not find getter for ' . get_class($entity));
	}
}
