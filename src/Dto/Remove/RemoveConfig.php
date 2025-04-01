<?php
declare(strict_types=1);

namespace Common\Dto\Remove;

use Attribute;

#[Attribute]
class RemoveConfig
{
	public function __construct(
		private readonly ?string $postRemove
	)
	{
	}
}
