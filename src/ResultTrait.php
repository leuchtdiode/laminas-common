<?php
namespace Common;

trait ResultTrait
{
	private bool $success;

	/**
	 * @var Error[]
	 */
	private array $errors = [];

	public function addError(Error $error): self
	{
		$this->errors[] = $error;
		return $this;
	}

	public function setSuccess(bool $success): self
	{
		$this->success = $success;
		return $this;
	}

	public function isSuccess(): bool
	{
		return $this->success;
	}

	/**
	 * @return Error[]
	 */
	public function getErrors(): array
	{
		return $this->errors;
	}

	/**
	 * @param Error[] $errors
	 */
	public function setErrors(array $errors): self
	{
		$this->errors = $errors;
		return $this;
	}

	public function hasErrors(): bool
	{
		return count($this->errors) > 0;
	}

	/**
	 * @param Error[] $errors
	 */
	public function addErrors(array $errors): self
	{
		foreach ($errors as $error)
		{
			$this->addError($error);
		}

		return $this;
	}
}
