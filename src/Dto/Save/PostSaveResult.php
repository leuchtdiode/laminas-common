<?php
declare(strict_types=1);

namespace Common\Dto\Save;

use Common\Dto\Dto;

class PostSaveResult
{
	private Dto $dto;

	public function getDto(): Dto
	{
		return $this->dto;
	}

	public function setDto(Dto $dto): void
	{
		$this->dto = $dto;
	}
}
