<?php
namespace Common\RequestData\PropertyDefinition;

use Laminas\Validator\Digits;

class Integer extends PropertyDefinition
{
	public static function create(): self
	{
		return new self();
	}

	public function valueIsEmpty($value): bool
	{
		return $value === null;
	}

	/**
	 */
	public function __construct()
	{
		parent::__construct();

		$this->addValidator(
			new Digits()
		);
	}
}
