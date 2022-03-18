<?php
namespace CommonTest\RequestData;

use Common\RequestData\Data;
use Common\RequestData\PropertyDefinition\Text;
use Psr\Container\ContainerInterface;

class TextTest extends Base
{
	/**
	 * @return string
	 */
	protected function getField()
	{
		return 'irrelevant';
	}

	/**
	 * @return string
	 */
	protected function getDataClass()
	{
		return TextTestData::class;
	}

	/**
	 * @return array
	 */
	public function myDataSet()
	{
		return [
			[ null, true, null ],
			[ '', true, null ],
			[ ' ', true, null ],
			[ 'test', false, 'test' ],
			[ "ich bin ein test\nmit linebreak", false, "ich bin ein test\nmit linebreak" ],
			[ "   test   ", false, "test" ],
		];
	}

	public function test_with_trim()
	{
		$data = new TextTestData($this->getApplicationServiceLocator(), false);

		$requestData = [
			$this->getField() => '   test   ',
		];

		$values = $data
			->setRequest($this->getRequestData($requestData))
			->getValues();

		$this->assertEquals('   test   ', $values->getRawValue($this->getField()));
	}
}

class TextTestData extends Data
{
	/**
	 * @var bool
	 */
	private $trim;

	public function __construct(ContainerInterface $container, bool $trim = true)
	{
		parent::__construct($container);

		$this->trim = $trim;
	}

	protected function getDefinitions(): array
	{
		return [
			Text::create(
				Text\CreateOptions::create()
					->setTrim($this->trim)
			)
				->setName('irrelevant')
				->setRequired(true),
		];
	}
}