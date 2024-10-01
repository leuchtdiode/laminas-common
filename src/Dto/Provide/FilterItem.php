<?php
declare(strict_types=1);

namespace Common\Dto\Provide;

class FilterItem
{
	private string  $type;
	private ?string $property;
	private mixed   $value;

	public static function fromArray(array $data): static
	{
		return (new static())
			->setType($data['type'] ?? null)
			->setProperty($data['property'] ?? null)
			->setValue($data['value'] ?? null);
	}

	public function getType(): ?string
	{
		return $this->type;
	}

	public function setType(?string $type): FilterItem
	{
		$this->type = $type;
		return $this;
	}

	public function getProperty(): ?string
	{
		return $this->property;
	}

	public function setProperty(?string $property): FilterItem
	{
		$this->property = $property;
		return $this;
	}

	public function getValue(): mixed
	{
		return $this->value;
	}

	public function setValue(mixed $value): FilterItem
	{
		$this->value = $value;
		return $this;
	}
}
