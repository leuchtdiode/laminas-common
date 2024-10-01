<?php
declare(strict_types=1);

namespace Common\Dto\Action;

use Common\Error;
use Common\Hydration\ObjectToArrayHydratorProperty;

class ParamInvalid extends Error
{
	public function __construct(
		private readonly string $message
	)
	{
	}

	public static function create(string $message): static
	{
	    return new static($message);
	}

	#[ObjectToArrayHydratorProperty] public function getCode(): string
	{
		return 'PARAM_INVALID';
	}

	#[ObjectToArrayHydratorProperty] public function getMessage(): string
	{
		return $this->message;
	}
}
