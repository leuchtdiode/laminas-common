<?php
declare(strict_types=1);

namespace Common\Dto\Save;

use Common\Error;
use Common\Hydration\ArrayHydratable;
use Common\Hydration\ObjectToArrayHydratorProperty;

class PropertyInvalidError extends Error implements ArrayHydratable
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

	#[ObjectToArrayHydratorProperty]
	public function getCode(): string
	{
		return 'PROPERTY_INVALID';
	}

	#[ObjectToArrayHydratorProperty]
	public function getMessage(): string
	{
		return $this->message;
	}
}
