<?php
namespace Common\Util;

class ArrayCreator
{
	private array $array = [];

	public static function create(): self
	{
		return new self();
	}

	public function addIfNotEmpty(mixed $value, ?string $key = null): self
	{
		if (empty($value))
		{
			return $this;
		}

		return $this->add($value, $key);
	}

	public function addIfNotNull(mixed $value, ?string $key = null): self
	{
		if ($value === null)
		{
			return $this;
		}

		return $this->add($value, $key);
	}

	public function add(mixed $value, ?string $key = null): self
	{
		if ($key !== null)
		{
			$this->array[$key] = $value;

			return $this;
		}

		$this->array[] = $value;

		return $this;
	}

	public function getArray(): array
	{
		return $this->array;
	}

	public function getJoined(string $delimiter): string
	{
		return implode($delimiter, $this->array);
	}
}