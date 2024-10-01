<?php
declare(strict_types=1);

namespace Common\Dto\Save;

use Common\Db\Entity;

class HandleItemResult
{
	private Entity $entity;

	public static function create(): static
	{
		return new static();
	}

	public function getEntity(): Entity
	{
		return $this->entity;
	}

	public function setEntity(Entity $entity): void
	{
		$this->entity = $entity;
	}
}
