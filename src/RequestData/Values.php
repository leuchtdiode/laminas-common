<?php
namespace Common\RequestData;

use Common\Error;

class Values
{
	/**
	 * @var Value[]
	 */
	private array $values = [];

	/**
	 * @param Value $value
	 */
	public function addValue(Value $value): void
	{
		$this->values[] = $value;
	}

	public function get(string $name): ?Value
	{
		foreach ($this->values as $value)
		{
			if ($value->getName() === $name)
			{
				return $value;
			}
		}

		return null;
	}

	public function getRawValue(string $name): mixed
	{
		$value = $this->get($name);

		return $value?->getValue();
	}

	public function valueIsPresent(string $name): bool
	{
		$value = $this->get($name);

		return $value && $value->isPresent();
	}

	/**
	 * @return Error[]
	 */
	public function getErrors(): array
	{
		$errors = [];

		foreach ($this->values as $value)
		{
			$errors = array_merge_recursive($errors, $value->getErrors());
		}

		return $errors;
	}

	public function hasErrors(): bool
	{
		return !empty($this->getErrors());
	}

	/**
	 * @return Value[]
	 */
	public function getValues(): array
	{
		return $this->values;
	}
}