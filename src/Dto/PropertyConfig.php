<?php
declare(strict_types=1);

namespace Common\Dto;

use Attribute;
use Laminas\Validator\ValidatorInterface;

#[Attribute]
class PropertyConfig
{
	/**
	 * @var ValidatorInterface[]
	 */
	public array $validators = [];

	private ?string $validationLabel;

	/**
	 * @param ValidatorInterface[] $validators
	 */
	public function __construct(array $validators = [], ?string $validationLabel = null)
	{
		$this->validators      = $validators;
		$this->validationLabel = $validationLabel;
	}
}
