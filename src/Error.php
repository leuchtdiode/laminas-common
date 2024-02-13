<?php
namespace Common;

use Common\Hydration\ArrayHydratable;
use Common\Hydration\ObjectToArrayHydratorProperty;

abstract class Error implements ArrayHydratable
{
	/**
	 * @var Error[]
	 */
	#[ObjectToArrayHydratorProperty]
	private array $subErrors = [];

	#[ObjectToArrayHydratorProperty]
	abstract public function getCode(): string;

	#[ObjectToArrayHydratorProperty]
	abstract public function getMessage(): string;

	/**
	 * @return Error[]
	 */
	public function getSubErrors(): array
	{
		return $this->subErrors;
	}

	/**
	 * @param Error $error
	 */
	public function addSubError(Error $error): void
	{
		$this->subErrors[] = $error;
	}
}