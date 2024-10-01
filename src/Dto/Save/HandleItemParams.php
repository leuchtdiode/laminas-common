<?php
declare(strict_types=1);

namespace Common\Dto\Save;

use Common\Dto\BaseDto;
use Common\Dto\BaseSaveData;

class HandleItemParams
{
	private ?string      $dtoKey = null;
	private Transaction  $transaction;
	private ?string      $dtoId  = null;
	private BaseSaveData $data;

	public static function create(): static
	{
		return new static();
	}

	public function getDtoKey(): ?string
	{
		return $this->dtoKey;
	}

	public function setDtoKey(?string $dtoKey): HandleItemParams
	{
		$this->dtoKey = $dtoKey;
		return $this;
	}

	public function getTransaction(): Transaction
	{
		return $this->transaction;
	}

	public function setTransaction(Transaction $transaction): HandleItemParams
	{
		$this->transaction = $transaction;
		return $this;
	}

	public function getDtoId(): ?string
	{
		return $this->dtoId;
	}

	public function setDtoId(?string $dtoId): HandleItemParams
	{
		$this->dtoId = $dtoId;
		return $this;
	}

	public function getData(): BaseSaveData
	{
		return $this->data;
	}

	public function setData(BaseSaveData $data): HandleItemParams
	{
		$this->data = $data;
		return $this;
	}
}
