<?php
declare(strict_types=1);

namespace Common\Dto\Save;

use Common\Dto\Dto;

class PostSaveParams
{
	private Dto  $dto;
	private bool $addition;

	public static function create(): static
	{
		return new static();
	}

	public function getDto(): Dto
	{
		return $this->dto;
	}

	public function setDto(Dto $dto): PostSaveParams
	{
		$this->dto = $dto;
		return $this;
	}

	public function isAddition(): bool
	{
		return $this->addition;
	}

	public function setAddition(bool $addition): PostSaveParams
	{
		$this->addition = $addition;
		return $this;
	}
}
