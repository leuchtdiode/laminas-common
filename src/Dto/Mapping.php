<?php
namespace Common\Dto;

use Common\Db\EntityRepository;
use Common\Db\Entity;
use Doctrine\Common\Collections\Collection;

abstract class Mapping
{
	abstract public function createSingle(Entity $entity, ?CreateOptions $createOptions = null): Dto;

	abstract protected function getRepository(): EntityRepository;

	/**
	 * @param Collection|Entity[] $entities
	 * @return Dto[]
	 */
	public function createMultiple(array $entities, ?CreateOptions $createOptions = null): array
	{
		if ($entities instanceof Collection)
		{
			$entities = $entities->toArray();
		}

		return array_map(
			function (Entity $entity) use ($createOptions)
			{
				return $this->createSingle($entity, $createOptions);
			},
			$entities
		);
	}

	public function getEntity(Dto $dto): Entity
	{
		return $this
			->getRepository()
			->find($dto->getId());
	}
}