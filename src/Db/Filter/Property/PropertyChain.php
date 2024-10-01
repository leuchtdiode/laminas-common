<?php
declare(strict_types=1);

namespace Common\Db\Filter\Property;

class PropertyChain
{
	/**
	 * @var PropertyChainItem[]
	 */
	private array $properties = [];

	public static function create(): static
	{
	    return new static();
	}

	/**
	 * @return PropertyChainItem[]
	 */
	public function getProperties(): array
	{
		return $this->properties;
	}

	/**
	 * @param PropertyChainItem[] $properties
	 */
	public function setProperties(array $properties): PropertyChain
	{
		$this->properties = $properties;
		return $this;
	}
}
