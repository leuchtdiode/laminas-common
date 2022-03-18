<?php
namespace Common\RequestData\Error;

use Common\Error;
use Common\Hydration\ObjectToArrayHydratorProperty;
use Common\Translator;

class PropertyIsInvalid extends Error
{
	private string $name;

	private string $message;

	private function __construct(string $name, string $message)
	{
		$this->name    = $name;
		$this->message = $message;
	}

	public static function create(string $name, string $message): self
	{
		return new self($name, $message);
	}

	#[ObjectToArrayHydratorProperty]
	public function getCode(): string
	{
		return 'PROPERTY_INVALID';
	}

	#[ObjectToArrayHydratorProperty]
	public function getMessage(): string
	{
		return Translator::translate($this->name . ' ist ungültig (' . $this->message . ')');
	}
}