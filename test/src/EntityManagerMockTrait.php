<?php

namespace CommonTest;

use Doctrine\ORM\Configuration;
use Doctrine\ORM\EntityManager;

trait EntityManagerMockTrait
{
	protected function getEntityManagerMock(): EntityManager
	{
		$entityManager = $this
			->getMockBuilder('Doctrine\ORM\EntityManager')
			->disableOriginalConstructor()
			->onlyMethods([
				'getConnection',
				'getClassMetadata',
				'close',
				'getConfiguration',
			])
			->getMock();

		$entityManager
			->method('getConfiguration')
			->willReturn(new Configuration());

		return $entityManager;
	}
}