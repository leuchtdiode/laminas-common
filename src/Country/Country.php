<?php
namespace Common\Country;

use Common\Hydration\ArrayHydratable;
use Common\Hydration\ObjectToArrayHydratorProperty;

class Country implements ArrayHydratable
{
	#[ObjectToArrayHydratorProperty]
	private string $isoCode;

	#[ObjectToArrayHydratorProperty]
	private string $label;

	public function __construct(string $isoCode, string $label)
	{
		$this->isoCode = $isoCode;
		$this->label   = $label;
	}

	public function getIsoCode(): string
	{
		return $this->isoCode;
	}

	public function getLabel(): string
	{
		return $this->label;
	}
}