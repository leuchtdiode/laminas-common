<?php
namespace Common\RequestData\PropertyDefinition;

use Laminas\I18n\Validator\DateTime as DateTimeValidator;

class DateTime extends PropertyDefinition
{
	public static function create(): self
	{
		return new self();
	}

	public function __construct()
	{
		parent::__construct();

		$this->addValidator(
			new DateTimeValidator(
				[
					'pattern' => 'Y-m-d H:i:s'
				]
			)
		);
	}
}
