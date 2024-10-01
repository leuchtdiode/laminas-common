<?php
declare(strict_types=1);

namespace Common\Dto\CreateOptions;

use Common\Dto\CreateOptions;

class Generic implements CreateOptions
{
	/**
	 * @var PropertyToLoad[]
	 */
	private array $propertiesToLoad = [];

	/**
	 * @var PropertyToIgnore[]
	 */
	private array $propertiesToIgnore = [];

	public static function create(): static
	{
		return new static();
	}

	public function addPropertyToLoad(PropertyToLoad $propertyToLoad): static
	{
		$this->propertiesToLoad[] = $propertyToLoad;
		return $this;
	}

	public function addPropertyToIgnore(PropertyToIgnore $propertyToIgnore): static
	{
		$this->propertiesToIgnore[] = $propertyToIgnore;
		return $this;
	}

	/**
	 * @param PropertyToLoad[] $propertiesToLoad
	 */
	public function setPropertiesToLoad(array $propertiesToLoad): Generic
	{
		$this->propertiesToLoad = $propertiesToLoad;
		return $this;
	}

	/**
	 * @return PropertyToLoad[]
	 */
	public function getPropertiesToLoad(): array
	{
		return $this->propertiesToLoad;
	}

	/**
	 * @return PropertyToIgnore[]
	 */
	public function getPropertiesToIgnore(): array
	{
		return $this->propertiesToIgnore;
	}

	/**
	 * @param PropertyToIgnore[] $propertiesToIgnore
	 */
	public function setPropertiesToIgnore(array $propertiesToIgnore): Generic
	{
		$this->propertiesToIgnore = $propertiesToIgnore;
		return $this;
	}
}
