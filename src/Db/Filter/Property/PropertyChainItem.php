<?php
declare(strict_types=1);

namespace Common\Db\Filter\Property;

class PropertyChainItem
{
	private string $name;
	
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
}
