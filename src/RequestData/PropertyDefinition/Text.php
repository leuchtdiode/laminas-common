<?php
namespace Common\RequestData\PropertyDefinition;

use Common\RequestData\PropertyDefinition\Text\CreateOptions;
use Laminas\Filter\StringTrim;

class Text extends PropertyDefinition
{
	public static function create(?CreateOptions $createOptions = null): self
	{
		return new self($createOptions);
	}

	/**
	 */
	public function __construct(?CreateOptions $createOptions = null)
	{
		parent::__construct();

		if (!$createOptions || $createOptions->isTrim())
		{
			$this->addFilter(
				new StringTrim()
			);
		}
	}
}