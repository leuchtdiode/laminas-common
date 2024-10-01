<?php
declare(strict_types=1);

namespace Common\Dto\CreateOptions;

class PropertyToIgnore
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

	public function setDtoKey(string $dtoKey): PropertyToIgnore
	{
		$this->dtoKey = $dtoKey;
		return $this;
	}

	public function getProperty(): string
	{
		return $this->property;
	}

	public function setProperty(string $property): PropertyToIgnore
	{
		$this->property = $property;
		return $this;
	}
}
