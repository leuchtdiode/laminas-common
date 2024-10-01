<?php
declare(strict_types=1);

namespace Common\Dto\Action;

use Common\Action\Meta;
use Common\Dto\Dto;
use Common\Error;

class ListResult
{
	/**
	 * @var Dto[]
	 */
	private array $items = [];

	private ?Meta $meta = null;

	/**
	 * @var Error[]
	 */
	private array $errors = [];

	public static function create(): static
	{
		return new static();
	}

	/**
	 * @return Dto[]
	 */
	public function getItems(): array
	{
		return $this->items;
	}

	/**
	 * @param Dto[] $items
	 */
	public function setItems(array $items): ListResult
	{
		$this->items = $items;
		return $this;
	}

	public function getMeta(): ?Meta
	{
		return $this->meta;
	}

	public function setMeta(?Meta $meta): ListResult
	{
		$this->meta = $meta;
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
	public function setErrors(array $errors): ListResult
	{
		$this->errors = $errors;
		return $this;
	}
}
