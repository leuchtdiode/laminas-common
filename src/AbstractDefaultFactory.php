<?php
namespace Common;

use Exception;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use ReflectionClass;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\AbstractFactoryInterface;
use ReflectionException;

abstract class AbstractDefaultFactory implements AbstractFactoryInterface
{
	private ContainerInterface $container;

	private string $requestedName;

	abstract protected function getNamespace();

	public function canCreate(
		ContainerInterface $container,
		$requestedName
	): bool
	{
		return str_starts_with($requestedName, $this->getNamespace() . '\\');
	}

	public function __invoke(
		ContainerInterface $container,
		$requestedName,
		array $options = null
	)
	{
		$this->container		= $container;
		$this->requestedName 	= $requestedName;

		$factoryClassName = $requestedName . 'Factory';

		if (class_exists($factoryClassName))
		{
			return (new $factoryClassName())->__invoke($container, $requestedName, $options);
		}

		try
		{
			if (($object = $this->tryToLoadWithReflection()))
			{
				return $object;
			}
		}
		catch (Exception $ex)
		{
			throw $ex;
		}

		return new $requestedName;
	}

	/**
	 * @throws NotFoundExceptionInterface
	 * @throws ContainerExceptionInterface
	 * @throws ReflectionException
	 */
	private function tryToLoadWithReflection(): ?object
	{
		$class = new ReflectionClass($this->requestedName);

		if(!($constructor = $class->getConstructor()))
		{
			return null;
		}

		if(!($params = $constructor->getParameters()))
		{
			return null;
		}

		$parameterInstances = [];

		foreach($params as $p)
		{
			$type = $p->getType();

			if($p->getName() === 'container')
			{
				$parameterInstances[] = $this->container;
			}
			else if($type && !$type->isBuiltin())
			{
				try
				{
					$parameterInstances[] = $this->container->get(
						$type->getName()
					);
				}
				catch (Exception $ex)
				{
					error_log($ex->getMessage());

					throw $ex;
				}
			}
			else if($type && $type->getName() === 'array' && $p->getName() === 'config')
			{
				$parameterInstances[] = $this->container->get('Config');
			}
		}

		return $class->newInstanceArgs($parameterInstances);
	}
}
