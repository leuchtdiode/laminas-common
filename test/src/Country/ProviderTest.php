<?php
namespace CommonTest\Country;

use Common\Country\Provider;
use Common\Translator;
use Exception;
use Laminas\I18n\Translator\Translator as I18nTranslator;
use Laminas\Mvc\I18n\Translator as MvcTranslator;
use PHPUnit\Framework\TestCase;

class ProviderTest extends TestCase
{
	const ANY_ISO_CODE = 'AT';
	const ANY_LOCALE   = 'de_DE';

	/**
	 *
	 */
	protected function setUp(): void
	{
		$translator = new I18nTranslator();

		Translator::setInstance(new MvcTranslator($translator));

		parent::setUp();
	}

	/**
	 * @throws Exception
	 */
	public function test_all()
	{
		$all = (new Provider())
			->all();

		$first = $all[0];

		$this->assertCount(249, $all);
		$this->assertEquals('ZW', $all[count($all) - 1]->getIsoCode());
		$this->assertEquals('AF', $first->getIsoCode());
	}

	/**
	 * @dataProvider isoCodeSet
	 * @param $locale
	 * @param $isoCode
	 * @param $expectedLabel
	 * @throws Exception
	 */
	public function test_results($locale, $isoCode, $expectedLabel)
	{
		Translator::getInstance()
			->setLocale($locale);

		$country = (new Provider())
			->byIsoCode($isoCode);

		$this->assertEquals($isoCode, $country->getIsoCode());
		$this->assertEquals($expectedLabel, $country->getLabel());
	}

	/**
	 */
	public function test_exception_thrown_on_invalid_locale()
	{
		$this->expectException(Exception::class);
		$this->expectExceptionMessage('Could not find country list file');

		Translator::getInstance()
			->setLocale('xx_XX');

		(new Provider())
			->byIsoCode(self::ANY_ISO_CODE);
	}

	/**
	 */
	public function test_exception_thrown_on_invalid_iso_code()
	{
		$this->expectException(Exception::class);
		$this->expectExceptionMessage('Could not find country with iso code XX');

		Translator::getInstance()
			->setLocale(self::ANY_LOCALE);

		(new Provider())
			->byIsoCode('xx');
	}

	/**
	 * @return array
	 */
	public function isoCodeSet()
	{
		return [
			[ 'de_DE', 'AT', 'Österreich' ],
			[ 'en_EN', 'AT', 'Austria' ],
			[ 'fr_FR', 'AT', 'Autriche' ],
			[ 'de_DE', 'DE', 'Deutschland' ],
			[ 'en_EN', 'DE', 'Germany' ],
			[ 'fr_FR', 'DE', 'Allemagne' ],
		];
	}
}
