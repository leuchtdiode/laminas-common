<?php
namespace Common\RequestData\PropertyDefinition;

use Laminas\Validator\Callback;

class ArrayList extends PropertyDefinition
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

		$isArrayValidator = new Callback(
			function ($value)
			{
				return is_array($value);
			}
		);
		$isArrayValidator->setMessage('Der Wert ist kein Array');

		$this->addValidator($isArrayValidator);
	}
}
