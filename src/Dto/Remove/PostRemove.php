<?php
declare(strict_types=1);

namespace Common\Dto\Remove;

interface PostRemove
{
	public function handle(PostRemoveParams $params): PostRemoveResult;
}
