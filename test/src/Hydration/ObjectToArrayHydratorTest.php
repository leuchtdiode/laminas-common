<?php
namespace CommonTest\Hydration;

use Common\Hydration\ArrayHydratable;
use Common\Hydration\ObjectToArrayHydrator;
use Common\Hydration\ObjectToArrayHydratorProperty;
use CommonTest\Base;
use DateTime;
use Exception;

class ObjectToArrayHydratorTest extends Base
{
	/**
	 * @throws Exception
	 */
	public function test_hydration()
	{
		$user = new User(
			1,
			'Alex',
			$dateTime = new DateTime('2022-03-18 22:00:05'),
			new Address('1010', true)
		);

		$this->assertEquals(
			[
				'id'           => 1,
				'name'         => 'Alex',
				'creationDate' => $dateTime->format('c'),
				'address'      => [
					'zip'     => '1010',
					'default' => true,
				],
			],
			ObjectToArrayHydrator::hydrate($user)
		);
	}
}

class User implements ArrayHydratable
{
	#[ObjectToArrayHydratorProperty]
	private int $id;

	#[ObjectToArrayHydratorProperty]
	private string $name;

	#[ObjectToArrayHydratorProperty]
	private DateTime $creationDate;

	#[ObjectToArrayHydratorProperty]
	private Address $address;

	public function __construct(int $id, string $name, DateTime $creationDate, Address $address)
	{
		$this->id           = $id;
		$this->name         = $name;
		$this->creationDate = $creationDate;
		$this->address      = $address;
	}

	/**
	 * @return int
	 */
	public function getId(): int
	{
		return $this->id;
	}

	/**
	 * @return string
	 */
	public function getName(): string
	{
		return $this->name;
	}

	public function getCreationDate(): DateTime
	{
		return $this->creationDate;
	}

	/**
	 * @return Address
	 */
	public function getAddress(): Address
	{
		return $this->address;
	}
}

class Address implements ArrayHydratable
{
	private string $zip;

	private bool $default;

	/**
	 * @param string $zip
	 * @param bool $default
	 */
	public function __construct(string $zip, bool $default)
	{
		$this->zip     = $zip;
		$this->default = $default;
	}

	#[ObjectToArrayHydratorProperty]
	public function getZip(): string
	{
		return $this->zip;
	}

	#[ObjectToArrayHydratorProperty]
	public function isDefault(): bool
	{
		return $this->default;
	}
}