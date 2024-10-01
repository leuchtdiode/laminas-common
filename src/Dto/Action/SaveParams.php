<?php
declare(strict_types=1);

namespace Common\Dto\Action;

use Laminas\Stdlib\RequestInterface;

class SaveParams
{
	private string           $dtoKey;
	private RequestInterface $request;
	private ?string          $id = null;

	public static function create(): static
	{
		return new static();
	}

	public function getDtoKey(): string
	{
		return $this->dtoKey;
	}

	public function setDtoKey(string $dtoKey): SaveParams
	{
		$this->dtoKey = $dtoKey;
		return $this;
	}

	public function getId(): ?string
	{
		return $this->id;
	}

	public function setId(?string $id): SaveParams
	{
		$this->id = $id;
		return $this;
	}

	public function getRequest(): RequestInterface
	{
		return $this->request;
	}

	public function setRequest(RequestInterface $request): SaveParams
	{
		$this->request = $request;
		return $this;
	}
}
