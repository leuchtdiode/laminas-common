<?php
declare(strict_types=1);

namespace Common\Dto;

class BaseRemoveParams
{
	private Dto $dto;

	public static function create(): static
	{
		return new static();
	}

	public function getDto(): Dto
	{
		return $this->dto;
	}

	public function setDto(Dto $dto): BaseRemoveParams
	{
		$this->dto = $dto;
		return $this;
	}
}
