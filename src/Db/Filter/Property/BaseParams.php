<?php
declare(strict_types=1);

namespace Common\Db\Filter\Property;

abstract class BaseParams
{
	private PropertyChain $propertyChain;

	public function getPropertyChain(): PropertyChain
	{
		return $this->propertyChain;
	}

	public function setPropertyChain(PropertyChain $propertyChain): BaseParams
	{
		$this->propertyChain = $propertyChain;
		return $this;
	}
}
