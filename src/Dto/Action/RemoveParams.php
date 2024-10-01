<?php
declare(strict_types=1);

namespace Common\Dto\Action;

class RemoveParams
{
	private string  $dtoKey;
	private ?string $id = null;

	public static function create(): static
	{
		return new static();
	}

	public function getDtoKey(): string
	{
		return $this->dtoKey;
	}

	public function setDtoKey(string $dtoKey): RemoveParams
	{
		$this->dtoKey = $dtoKey;
		return $this;
	}

	public function getId(): ?string
	{
		return $this->id;
	}

	public function setId(?string $id): RemoveParams
	{
		$this->id = $id;
		return $this;
	}
}
