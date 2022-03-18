<?php
namespace Common\Country;

use Common\Translator;
use Exception;
use Laminas\Validator\AbstractValidator;

class Validator extends AbstractValidator
{
	const INVALID = 'invalid';

	private Provider $countryProvider;

	public function __construct(Provider $countryProvider)
	{
		$this->countryProvider = $countryProvider;

		parent::__construct(
			[
				'messageTemplates' => [
					self::INVALID => Translator::translate('Land %value% ist nicht zulässig')
				],
			]
		);
	}

	public function isValid($value): bool
	{
		$this->setValue($value);

		try
		{
			$this->countryProvider->byIsoCode($value);

			return true;
		}
		catch (Exception $ex)
		{
			// do nothing
		}

		$this->error(self::INVALID);

		return false;
	}
}
