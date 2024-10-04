<?php
declare(strict_types=1);

namespace Common\Dto\Mapping;

class DataManipulationResult
{
	private array $data;

	public function getData(): array
	{
		return $this->data;
	}

	public function setData(array $data): void
	{
		$this->data = $data;
	}
}
