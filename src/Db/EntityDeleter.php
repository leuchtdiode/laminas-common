<?php
namespace Common\Db;

use Doctrine\ORM\EntityManager;
use Exception;

class EntityDeleter
{
	protected EntityManager $entityManager;

	public function __construct(EntityManager $entityManager)
	{
		$this->entityManager = $entityManager;
	}

	/**
	 * @throws Exception
	 */
	public function delete(Entity $entity, bool $flush = true)
	{
		$this->entityManager->remove($entity);
		
		if ($flush)
		{
			$this->entityManager->flush($entity);
		}
	}
}