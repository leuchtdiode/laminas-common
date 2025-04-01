<?php
declare(strict_types=1);

namespace Common\Dto\Remove;

use Common\Db\Entity;
use Common\Dto\Dto;

class PostRemoveParams
{
	private Dto    $dto;
	private Entity $entity;

	public static function create(): static
	{
		return new static();
	}

	public function getDto(): Dto
	{
		return $this->dto;
	}

	public function setDto(Dto $dto): PostRemoveParams
	{
		$this->dto = $dto;
		return $this;
	}

	public function getEntity(): Entity
	{
		return $this->entity;
	}

	public function setEntity(Entity $entity): PostRemoveParams
	{
		$this->entity = $entity;
		return $this;
	}
}
