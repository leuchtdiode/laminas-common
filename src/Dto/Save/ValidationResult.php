<?php
declare(strict_types=1);

namespace Common\Dto\Save;

use Common\Error;

class ValidationResult
{
	/**
	 * @var Error[]
	 */
	private array $errors = [];

	public function hasErrors(): bool
	{
		return !empty($this->errors);
	}

	public function addError(Error $error): void
	{
		$this->errors[] = $error;
	}

	/**
	 * @return Error[]
	 */
	public function getErrors(): array
	{
		return $this->errors;
	}
}
