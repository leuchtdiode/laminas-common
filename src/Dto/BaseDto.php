<?php
declare(strict_types=1);

namespace Common\Dto;

use Common\Hydration\ArrayHydratable;
use Common\Util\StringUtil;
use LogicException;

class BaseDto implements Dto, ArrayHydratable
{
	private string $key;
	private array  $data;

	public function __construct(string $key, array $data)
	{
		$this->key  = $key;
		$this->data = $data;
	}

	public function __call(string $name, array $arguments)
	{
		$property = null;

		if (
			StringUtil::startsWith($name, 'get')
		)
		{
			$property = lcfirst(substr($name, 3));
		}

		if (
			StringUtil::startsWith($name, 'is')
		)
		{
			$property = lcfirst(substr($name, 2));
		}

		if (!$property || !array_key_exists($property, $this->data))
		{
			throw new LogicException('Invalid getter called (' . $name . ')');
		}

		return $this->data[$property];
	}

	public function getKey(): string
	{
		return $this->key;
	}

	public function getId(): mixed
	{
		return $this->data['id'];
	}

	public function getData(): array
	{
		return $this->data;
	}
}
