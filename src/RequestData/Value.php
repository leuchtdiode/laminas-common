<?php
namespace Common\RequestData;

use Common\Error;

class Value
{
	private string $name;

	private mixed $value = null;

	private bool $present = false;

	/**
	 * @var Error[]
	 */
	private array $errors = [];

	/**
	 * @param Error $error
	 */
	public function addError(Error $error): void
	{
		$this->errors[] = $error;
	}

	public function hasErrors(): bool
	{
		return !empty($this->errors);
	}

	public function setName(string $name): Value
	{
		$this->name = $name;
		return $this;
	}

	public function setValue(mixed $value): void
	{
		$this->value = $value;
	}

	public function setPresent(bool $present): void
	{
		$this->present = $present;
	}

	public function getName(): string
	{
		return $this->name;
	}

	public function getValue(): mixed
	{
		return $this->value;
	}

	public function isPresent(): bool
	{
		return $this->present;
	}

	/**
	 * @return Error[]
	 */
	public function getErrors(): array
	{
		return $this->errors;
	}
}