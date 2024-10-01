<?php
declare(strict_types=1);

namespace Common\Dto;

use Attribute;

#[Attribute]
class MappingConfig
{
	/**
	 * @var string[]
	 */
	public array $propertiesToIgnoreByDefault;

	/**
	 * @var string[]
	 */
	private array $forceProperties;

	/**
	 * @param string[] $propertiesToIgnoreByDefault
	 * @param string[] $forceProperties
	 */
	public function __construct(array $propertiesToIgnoreByDefault = [], array $forceProperties = [])
	{
		$this->propertiesToIgnoreByDefault = $propertiesToIgnoreByDefault;
		$this->forceProperties             = $forceProperties;
	}
}
