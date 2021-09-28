<?php
namespace Common\RequestData\PropertyDefinition;

use Laminas\Filter\StringTrim;
use Laminas\Validator\EmailAddress;

class Email extends PropertyDefinition
{
	/**
	 * @return Email
	 */
	public static function create()
	{
		return new self();
	}

	/**
	 */
	public function __construct()
	{
		parent::__construct();

		$this->addFilter(
			new StringTrim()
		);

		$this->addValidator(
			new EmailAddress()
		);
	}
}
