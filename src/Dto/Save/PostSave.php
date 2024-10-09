<?php
declare(strict_types=1);

namespace Common\Dto\Save;

interface PostSave
{
	public function handle(PostSaveParams $params): PostSaveResult;
}
