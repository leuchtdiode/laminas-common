<?php
declare(strict_types=1);

namespace Common\Db\Filter\Property;

class InParams extends BaseParams
{
	private array $values;

	public static function create(): static
	{
	    return new static();
	}

	public function getValues(): array
	{
		return $this->values;
	}

	public function setValues(array $values): InParams
	{
		$this->values = $values;
		return $this;
	}
}
