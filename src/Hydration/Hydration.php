<?php
declare(strict_types=1);

namespace Common\Hydration;

use Attribute;

#[Attribute]
class Hydration
{
	/**
	 * @param string[] $excludedProperties
	 */
	public function __construct(
		public array $excludedProperties = []
	)
	{
	}
}
