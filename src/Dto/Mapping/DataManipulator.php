<?php
declare(strict_types=1);

namespace Common\Dto\Mapping;

interface DataManipulator
{
	public function manipulate(DataManipulationParams $params): DataManipulationResult;
}
