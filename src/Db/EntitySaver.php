<?php
namespace Common\Db;

use Doctrine\ORM\EntityManager;
use Throwable;

class EntitySaver
{
	public function __construct(
		protected EntityManager $entityManager
	)
	{
	}

	/**
	 * @throws Throwable
	 */
	public function save(Entity $entity, bool $flush = true): void
	{
		$this->entityManager->persist($entity);

		if ($flush)
		{
			$this->entityManager->flush($entity);
		}
	}

	/**
	 * @throws Throwable
	 */
	public function flush(): void
	{
		$this->entityManager->flush();
	}
}