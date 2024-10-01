<?php
declare(strict_types=1);

namespace Common\Dto\CreateOptions;

class PropertyToLoad
{
	private string $dtoKey;
	private string $property;

	public static function create(): static
	{
	    return new static();
	}

	public function getDtoKey(): string
	{
		return $this->dtoKey;
	}

	public function setDtoKey(string $dtoKey): PropertyToLoad
	{
		$this->dtoKey = $dtoKey;
		return $this;
	}

	public function getProperty(): string
	{
		return $this->property;
	}

	public function setProperty(string $property): PropertyToLoad
	{
		$this->property = $property;
		return $this;
	}
}
