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

	public static function buildFromString(string $string): static
	{
		$properties = [];

		foreach (explode('.', $string) as $property)
		{
			$properties[] = PropertyChainItem::create()
				->setName($property);
		}

		$chain = new static();
		$chain->setProperties($properties);

		return $chain;
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
