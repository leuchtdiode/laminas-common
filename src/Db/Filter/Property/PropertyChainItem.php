<?php
declare(strict_types=1);

namespace Common\Db\Filter\Property;

class PropertyChainItem
{
	private string $name;

	/**
	 * @var BaseParams[]
	 */
	private array $params = [];

	public static function create(): static
	{
		return new static();
	}

	public function getName(): string
	{
		return $this->name;
	}

	public function setName(string $name): PropertyChainItem
	{
		$this->name = $name;
		return $this;
	}

	/**
	 * @return BaseParams[]
	 */
	public function getParams(): array
	{
		return $this->params;
	}

	/**
	 * @param BaseParams[] $params
	 */
	public function setParams(array $params): PropertyChainItem
	{
		$this->params = $params;
		return $this;
	}
}
