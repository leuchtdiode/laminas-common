<?php
namespace Common\RequestData\PropertyDefinition;

use Laminas\Validator\Date as DateValidator;

class Date extends PropertyDefinition
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
			new DateValidator()
		);
	}
}
