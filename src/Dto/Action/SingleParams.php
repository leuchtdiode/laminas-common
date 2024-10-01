<?php
declare(strict_types=1);

namespace Common\Dto\Action;

use Laminas\Stdlib\RequestInterface;

class SingleParams
{
	private string $dtoKey;
	private string $dtoId;
	private array  $getParams;

	public static function create(): static
	{
		return new static();
	}

	public function getDtoKey(): string
	{
		return $this->dtoKey;
	}

	public function setDtoKey(string $dtoKey): SingleParams
	{
		$this->dtoKey = $dtoKey;
		return $this;
	}

	public function getDtoId(): string
	{
		return $this->dtoId;
	}

	public function setDtoId(string $dtoId): SingleParams
	{
		$this->dtoId = $dtoId;
		return $this;
	}

	public function getGetParams(): array
	{
		return $this->getParams;
	}

	public function setGetParams(array $getParams): SingleParams
	{
		$this->getParams = $getParams;
		return $this;
	}
}
