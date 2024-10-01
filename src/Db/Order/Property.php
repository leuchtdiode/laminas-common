<?php
declare(strict_types=1);

namespace Common\Db\Order;

class Property extends AscOrDesc
{
	private string $property;

	public function setProperty(string $property): Property
	{
		$this->property = $property;
		return $this;
	}

	protected function getField(): string
	{
		return 't.' . $this->property;
	}
}
