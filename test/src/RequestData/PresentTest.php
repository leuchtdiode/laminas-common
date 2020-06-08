<?php
namespace CommonTest\RequestData;

use Common\RequestData\Data;
use Common\RequestData\PropertyDefinition\Text;
use CommonTest\Base as CommonBase;
use Exception;

class PresentTest extends CommonBase
{
	/**
	 * @throws Exception
	 */
	public function test_present()
	{
		$data = new PresentData($this->getApplicationServiceLocator());
		$data->setData(
			[
				'testValue' => 'test',
			]
		);

		$values = $data->getValues();

		$this->assertTrue(
			$values
				->get('testValue')
				->isPresent()
		);
	}

	/**
	 * @throws Exception
	 */
	public function test_present_with_null()
	{
		$data = new PresentData($this->getApplicationServiceLocator());
		$data->setData(
			[
				'testValue' => null,
			]
		);

		$values = $data->getValues();

		$this->assertTrue(
			$values
				->get('testValue')
				->isPresent()
		);
	}

	/**
	 * @throws Exception
	 */
	public function test_not_present()
	{
		$data = new PresentData($this->getApplicationServiceLocator());
		$data->setData([]);

		$values = $data->getValues();

		$this->assertFalse(
			$values
				->get('testValue')
				->isPresent()
		);
	}

	/**
	 * @throws Exception
	 */
	public function test_nested_present()
	{
		$data = new PresentNestedData($this->getApplicationServiceLocator());
		$data->setData([
			'address' => [
				'zip' => '1010',
			],
		]);

		$values = $data->getValues();

		$this->assertTrue(
			$values
				->get('address.zip')
				->isPresent()
		);
	}

	/**
	 * @throws Exception
	 */
	public function test_nested_present_with_null()
	{
		$data = new PresentNestedData($this->getApplicationServiceLocator());
		$data->setData([
			'address' => [
				'zip' => null,
			],
		]);

		$values = $data->getValues();

		$this->assertTrue(
			$values
				->get('address.zip')
				->isPresent()
		);
	}

	/**
	 * @throws Exception
	 */
	public function test_nested_not_present()
	{
		$data = new PresentNestedData($this->getApplicationServiceLocator());
		$data->setData([
			'address' => [],
		]);

		$values = $data->getValues();

		$this->assertFalse(
			$values
				->get('address.zip')
				->isPresent()
		);
	}
}

class PresentData extends Data
{
	/**
	 * @inheritDoc
	 */
	protected function getDefinitions()
	{
		return [
			Text::create()
				->setName('testValue')
				->setRequired(false),
		];
	}
}

class PresentNestedData extends Data
{
	/**
	 * @inheritDoc
	 */
	protected function getDefinitions()
	{
		return [
			Text::create()
				->setName('address.zip')
				->setRequired(false),
		];
	}
}