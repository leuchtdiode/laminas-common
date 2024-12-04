<?php
declare(strict_types=1);

namespace Common\Dto\Mapping;

use Common\Db\Entity;

class DataManipulationParams
{
	private array  $data;
	private Entity $entity;

	public static function create(): static
	{
		return new static();
	}

	public function getData(): array
	{
		return $this->data;
	}

	public function setData(array $data): DataManipulationParams
	{
		$this->data = $data;
		return $this;
	}

	public function getEntity(): Entity
	{
		return $this->entity;
	}

	public function setEntity(Entity $entity): DataManipulationParams
	{
		$this->entity = $entity;
		return $this;
	}
}
