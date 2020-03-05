<?php
namespace Common;

use Laminas\Mvc\I18n\Translator as LaminasTranslator;

class Translator
{
	/**
	 * @var LaminasTranslator
	 */
	private static $instance;

	/**
	 * @param LaminasTranslator $translator
	 */
	public static function setInstance(LaminasTranslator $translator)
	{
		self::$instance = $translator;
	}

	/**
	 * @return LaminasTranslator
	 */
	public static function getInstance()
	{
		return self::$instance;
	}

	/**
	 * @param string $text
	 * @return string
	 */
	public static function translate(string $text)
	{
		return self::$instance->translate($text);
	}

	/**
	 * @return string
	 */
	public static function getLocale()
	{
		return self::$instance->getLocale();
	}

	/**
	 * @return string
	 */
	public static function getLanguage()
	{
		list($language) = explode('_', self::getLocale());

		return $language;
	}
}
