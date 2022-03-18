<?php
namespace Common\RequestData\Error;

use Common\Error;
use Common\Hydration\ObjectToArrayHydratorProperty;
use Common\Translator;

class PropertyIsMandatory extends Error
{
	private string $name;

	private function __construct(string $name)
	{
		$this->name = $name;
	}

	public static function create(string $name): self
	{
		return new self($name);
	}

	#[ObjectToArrayHydratorProperty]
	public function getCode(): string
	{
		return 'MANDATORY_PROPERTY_MISSING';
	}

	#[ObjectToArrayHydratorProperty]
	public function getMessage(): string
	{
		return Translator::translate($this->name . ' darf nicht leer sein');
	}
}