<?php
declare(strict_types=1);

namespace Common\Dto\Action;

use Common\ResultTrait;
use Common\Dto\BaseDto;

class SaveResult
{
	use ResultTrait;

	private ?BaseDto $dto = null;

	public static function create(): static
	{
		return new static();
	}

	public function getDto(): ?BaseDto
	{
		return $this->dto;
	}

	public function setDto(?BaseDto $dto): void
	{
		$this->dto = $dto;
	}
}
