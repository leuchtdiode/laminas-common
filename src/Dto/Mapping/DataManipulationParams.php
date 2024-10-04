<?php
declare(strict_types=1);

namespace Common\Dto\Mapping;

class DataManipulationParams
{
	private array $data;
	
	public static function create(): static
	{
	    return new static();
	}

	public function getData(): array
	{
		return $this->data;
	}

	public function setData(array $data): DataManipulationParams
	{
		$this->data = $data;
		return $this;
	}
}
