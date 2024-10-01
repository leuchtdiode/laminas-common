<?php
declare(strict_types=1);

namespace Common\Dto;

use Common\Util\StringUtil;
use Exception;

class BaseSaveData
{
	private array $data;

	public function __construct(array $data = [])
	{
		$this->data = $data;
	}

	public static function create(): static
	{
		return new static();
	}

	public static function fromArray(array $data): static
	{
		return new static($data);
	}

	public function getValue(string $property): mixed
	{
		return $this->data[$property] ?? null;
	}

	/**
	 * @throws Exception
	 */
	public function __call(string $name, $arguments): static
	{
		if (!StringUtil::startsWith($name, 'set'))
		{
			throw new Exception('Invalid method called');
		}

		$value = $arguments[0];

		$property = lcfirst(str_replace('set', '', $name));

		$this->data[$property] = $value;

		return $this;
	}

	public function getData(): array
	{
		return $this->data;
	}
}
