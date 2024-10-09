<?php
declare(strict_types=1);

namespace Common\Dto;

use Attribute;

#[Attribute]
class MappingConfig
{
	/**
	 * @param string[] $propertiesToIgnoreByDefault
	 * @param string[] $forceProperties
	 */
	public function __construct(
		private readonly array $propertiesToIgnoreByDefault = [],
		private readonly array $forceProperties = [],
		private readonly ?string $dataManipulator = null
	)
	{
	}
}
