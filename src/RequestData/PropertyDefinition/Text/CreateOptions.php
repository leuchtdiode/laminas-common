<?php
namespace Common\RequestData\PropertyDefinition\Text;

class CreateOptions
{
	private bool $trim = true;

	public static function create(): self
	{
		return new self();
	}

	public function isTrim(): bool
	{
		return $this->trim;
	}

	public function setTrim(bool $trim): CreateOptions
	{
		$this->trim = $trim;
		return $this;
	}
}