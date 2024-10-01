<?php
declare(strict_types=1);

namespace Common\Dto\Action;

use Common\Error;
use Common\Hydration\ObjectToArrayHydratorProperty;

class DtoNotFound extends Error
{
	#[ObjectToArrayHydratorProperty] public function getCode(): string
	{
		return 'DTO_NOT_FOUND';
	}

	#[ObjectToArrayHydratorProperty] public function getMessage(): string
	{
		return 'DTO not found';
	}
}
