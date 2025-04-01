<?php
declare(strict_types=1);

namespace Common\Dto;

use Common\Db\EntityRepository;
use Common\Dto\Remove\RemoveConfig;
use Doctrine\ORM\EntityManager;
use Psr\Container\ContainerInterface;
use ReflectionClass;
use Throwable;

abstract class BaseRemover
{
	public function __construct(
		private readonly ContainerInterface $container,
		private readonly KeyConfig $keyConfig,
		private readonly EntityManager $entityManager
	)
	{
	}

	/**
	 * @throws Throwable
	 */
	public function remove(BaseRemoveParams $params): BaseRemoveResult
	{
		/**
		 * @var BaseDto $dto
		 */
		$dto = $params->getDto();

		$dbNamespace = $this->keyConfig->getDbNamespace($dto->getKey());

		/**
		 * @var EntityRepository $repository
		 */
		$repository = $this->container->get($dbNamespace . '\Repository');

		$entity = $repository->find($dto->getId());

		$result = new BaseRemoveResult();
		$result->setSuccess(false);

		if (!$entity)
		{
			$result->setSuccess(true);
			return $result;
		}

		$this->entityManager->remove($entity);
		$this->entityManager->flush();

		$removeConfig    = (new ReflectionClass($this))->getAttributes(RemoveConfig::class)[0] ?? null;
		$postRemoveClass = $removeConfig?->getArguments()['postRemove'] ?? null;

		if ($postRemoveClass)
		{
			/**
			 * @var Remove\PostRemove $postSave
			 */
			$postRemove = $this->container->get($postRemoveClass);

			$postRemove
				->handle(
					Remove\PostRemoveParams::create()
						->setDto($dto)
						->setEntity($entity)
				);
		}

		$result->setSuccess(true);

		return $result;
	}
}
