<?php
namespace Common\RequestData\PropertyDefinition;

use Common\RequestData\Transformer\Boolean as BooleanTransformer;

class Boolean extends PropertyDefinition
{
	public static function create(): self
	{
		return new self();
	}

	public function valueIsEmpty($value): bool
	{
		return $value === null;
	}

	/**
	 */
	public function __construct()
	{
		$this->setTransformer(BooleanTransformer::class);

		parent::__construct();
	}
}