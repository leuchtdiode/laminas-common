<?php
namespace Common\Db;

use Doctrine\ORM\EntityManager;
use Exception;

class EntitySaver
{
	protected EntityManager $entityManager;

	public function __construct(EntityManager $entityManager)
	{
		$this->entityManager = $entityManager;
	}

	public function save(Entity $entity, bool $flush = true): void
	{
		$this->entityManager->persist($entity);

		if ($flush)
		{
			$this->entityManager->flush($entity);
		}
	}

	/**
	 * @throws Exception
	 */
	public function flush()
	{
		$this->entityManager->flush();
	}
}