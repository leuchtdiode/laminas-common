<?php
declare(strict_types=1);

namespace Common\Dto;

use Attribute;

#[Attribute]
class EntityConfig
{
	public string $dtoKey;

	/**
	 * @param string $dtoClass
	 */
	public function __construct(string $dtoKey)
	{
		$this->dtoKey = $dtoKey;
	}
}
