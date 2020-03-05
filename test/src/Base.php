<?php
namespace CommonTest;

use Common\Translator;
use Laminas\I18n\Translator\Translator as I18nTranslator;
use Laminas\Mvc\I18n\Translator as MvcTranslator;
use Laminas\Test\PHPUnit\Controller\AbstractControllerTestCase;

class Base extends AbstractControllerTestCase
{
	/**
	 *
	 */
	protected function setUp(): void
	{
		$this->setApplicationConfig(
			include __DIR__ . '/../../config/test.config.php'
		);

		parent::setUp();
	}

	/**
	 *
	 */
	protected function setDummyTranslator()
	{
		$translator = new I18nTranslator();

		Translator::setInstance(new MvcTranslator($translator));
	}
}
