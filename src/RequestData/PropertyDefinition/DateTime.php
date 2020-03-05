<?php
namespace Common\RequestData\PropertyDefinition;

use Laminas\I18n\Validator\DateTime as DateTimeValidator;

class DateTime extends PropertyDefinition
{
	/**
	 * @return DateTime
	 */
	public static function create()
	{
		return new self();
	}

	/**
	 */
	public function __construct()
	{
		$this->addValidator(
			new DateTimeValidator(
				[
					'pattern' => 'Y-m-d H:i:s'
				]
			)
		);
	}
}
