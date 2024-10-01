<?php
declare(strict_types=1);

namespace Common\Dto;

class BaseSaveParams
{
	private ?string      $dtoId = null;
	private BaseSaveData $data;
	private bool         $flush = true;

	public static function create(): static
	{
		return new static();
	}

	public function getDtoId(): ?string
	{
		return $this->dtoId;
	}

	public function setDtoId(?string $dtoId): BaseSaveParams
	{
		$this->dtoId = $dtoId;
		return $this;
	}

	public function getData(): BaseSaveData
	{
		return $this->data;
	}

	public function setData(BaseSaveData $data): BaseSaveParams
	{
		$this->data = $data;
		return $this;
	}

	public function isFlush(): bool
	{
		return $this->flush;
	}

	public function setFlush(bool $flush): BaseSaveParams
	{
		$this->flush = $flush;
		return $this;
	}
}
