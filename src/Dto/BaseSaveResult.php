<?php
declare(strict_types=1);

namespace Common\Dto;

use Common\ResultTrait;

class BaseSaveResult
{
	use ResultTrait;

	private ?Dto $dto = null;

	public function getDto(): ?Dto
	{
		return $this->dto;
	}

	public function setDto(?Dto $dto): void
	{
		$this->dto = $dto;
	}
}
