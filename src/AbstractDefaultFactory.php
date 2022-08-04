<?php
namespace Common;

use Common\Db\EntityRepository;
use Doctrine\ORM\EntityManager;
use Exception;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\AbstractFactoryInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use ReflectionClass;
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

	/**
	 * @throws ContainerExceptionInterface
	 * @throws NotFoundExceptionInterface
	 * @throws ReflectionException
	 * @throws Exception
	 */
	public function __invoke(
		ContainerInterface $container,
		$requestedName,
		array $options = null
	)
	{
		$this->container     = $container;
		$this->requestedName = $requestedName;

		$factoryClassName = $requestedName . 'Factory';

		if (class_exists($factoryClassName))
		{
			return (new $factoryClassName())->__invoke($container, $requestedName, $options);
		}

		if (($object = $this->tryToLoadWithReflection()))
		{
			return $object;
		}

		return new $requestedName;
	}

	/**
	 * @throws NotFoundExceptionInterface
	 * @throws ContainerExceptionInterface
	 * @throws ReflectionException
	 * @throws Exception
	 */
	private function tryToLoadWithReflection(): ?object
	{
		$class = new ReflectionClass($this->requestedName);

		if (
			($parentClass = $class->getParentClass())
			&& $parentClass->getName() === EntityRepository::class
		)
		{
			return $this->getEntityRepository($class->getNamespaceName());
		}

		if (!($constructor = $class->getConstructor()))
		{
			return null;
		}

		if (!($params = $constructor->getParameters()))
		{
			return null;
		}

		$parameterInstances = [];

		foreach ($params as $p)
		{
			$type = $p->getType();

			if ($p->getName() === 'container')
			{
				$parameterInstances[] = $this->container;
			}
			else
			{
				if ($type && !$type->isBuiltin())
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
				else
				{
					if ($type && $type->getName() === 'array' && $p->getName() === 'config')
					{
						$parameterInstances[] = $this->container->get('Config');
					}
				}
			}
		}

		return $class->newInstanceArgs($parameterInstances);
	}

	/**
	 * @throws ContainerExceptionInterface
	 * @throws NotFoundExceptionInterface
	 * @throws Exception
	 */
	private function getEntityRepository(string $namespace): ?EntityRepository
	{
		$entityClass = $namespace . '\Entity';

		if (!class_exists($entityClass))
		{
			throw new Exception('Repository can not be created without Entity.php in namespace');
		}

		/**
		 * @var EntityManager $entityManager
		 */
		$entityManager = $this->container->get(EntityManager::class);

		return $entityManager->getRepository($entityClass);
	}
}