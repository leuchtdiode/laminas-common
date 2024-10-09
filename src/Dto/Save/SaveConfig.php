<?php
declare(strict_types=1);

namespace Common\Dto\Save;

use Attribute;

#[Attribute]
class SaveConfig
{
	public function __construct(
		private readonly ?string $postSave
	)
	{
	}
}
