<?php
declare(strict_types=1);

namespace Common\Dto;

use Common\Db\Entity;

class DefaultMapParams
{
	private Entity         $entity;
	private int            $level            = 1;
	private bool           $resolveRelations = true;
	private ?CreateOptions $createOptions    = null;

	public static function create(): static
	{
		return new static();
	}

	public function getEntity(): Entity
	{
		return $this->entity;
	}

	public function setEntity(Entity $entity): DefaultMapParams
	{
		$this->entity = $entity;
		return $this;
	}

	public function getLevel(): int
	{
		return $this->level;
	}

	public function setLevel(int $level): DefaultMapParams
	{
		$this->level = $level;
		return $this;
	}

	public function isResolveRelations(): bool
	{
		return $this->resolveRelations;
	}

	public function setResolveRelations(bool $resolveRelations): DefaultMapParams
	{
		$this->resolveRelations = $resolveRelations;
		return $this;
	}

	public function getCreateOptions(): ?CreateOptions
	{
		return $this->createOptions;
	}

	public function setCreateOptions(?CreateOptions $createOptions): DefaultMapParams
	{
		$this->createOptions = $createOptions;
		return $this;
	}
}
