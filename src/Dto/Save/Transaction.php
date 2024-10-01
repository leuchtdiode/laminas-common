<?php
declare(strict_types=1);

namespace Common\Dto\Save;

use Common\Db\Entity;
use Common\Error;

class Transaction
{
	/**
	 * @var Error[]
	 */
	private array $validationErrors = [];

	private Entity $sourceEntity;

	/**
	 * @var Entity[]
	 */
	private array $entities = [];

	public function hasValidationErrors(): bool
	{
		return !empty($this->validationErrors);
	}

	public function addValidationError(Error $error): void
	{
		$this->validationErrors[] = $error;
	}

	/**
	 * @param Error[] $errors
	 */
	public function addValidationErrors(array $errors): void
	{
		$this->validationErrors = array_merge($this->validationErrors, $errors);
	}

	public function addEntity(Entity $entity): void
	{
		$this->entities[] = $entity;
	}

	public function getSourceEntity(): Entity
	{
		return $this->sourceEntity;
	}

	public function setSourceEntity(Entity $sourceEntity): void
	{
		$this->sourceEntity = $sourceEntity;
	}

	/**
	 * @return Error[]
	 */
	public function getValidationErrors(): array
	{
		return $this->validationErrors;
	}

	/**
	 * @return Entity[]
	 */
	public function getEntities(): array
	{
		return $this->entities;
	}
}
