<?php
declare(strict_types=1);

namespace Common\Db\Filter\Property;

abstract class BaseParams
{
	private ?string        $property      = null;
	private ?PropertyChain $propertyChain = null;

	public function getProperty(): ?string
	{
		return $this->property;
	}

	public function setProperty(?string $property): BaseParams
	{
		$this->property = $property;
		return $this;
	}

	public function getPropertyChain(): ?PropertyChain
	{
		return $this->propertyChain;
	}

	public function setPropertyChain(?PropertyChain $propertyChain): BaseParams
	{
		$this->propertyChain = $propertyChain;
		return $this;
	}
}
