<?php
declare(strict_types=1);

namespace Common\Dto\Action;

use Common\Dto\Dto;
use Common\Error;

class SingleResult
{
	private ?Dto $item = null;

	/**
	 * @var Error[]
	 */
	private array $errors = [];

	public static function create(): static
	{
		return new static();
	}

	public function getItem(): ?Dto
	{
		return $this->item;
	}

	public function setItem(?Dto $item): SingleResult
	{
		$this->item = $item;
		return $this;
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
	public function setErrors(array $errors): SingleResult
	{
		$this->errors = $errors;
		return $this;
	}
}
