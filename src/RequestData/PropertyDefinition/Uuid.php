<?php
namespace Common\RequestData\PropertyDefinition;

use Laminas\Validator\Uuid as UuidValidator;

class Uuid extends PropertyDefinition
{
	public static function create(): self
	{
		return new self();
	}

	/**
	 */
	public function __construct()
	{
		parent::__construct();

		$this->addValidator(
			new UuidValidator()
		);
	}
}
