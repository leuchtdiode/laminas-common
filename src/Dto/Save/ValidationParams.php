<?php
declare(strict_types=1);

namespace Common\Dto\Save;

use Doctrine\ORM\Mapping\ClassMetadata;
use ReflectionProperty;

class ValidationParams
{
	private ClassMetadata      $classMetadata;
	private ReflectionProperty $property;
	private mixed              $value;

	public static function create(): static
	{
		return new static();
	}

	public function getClassMetadata(): ClassMetadata
	{
		return $this->classMetadata;
	}

	public function setClassMetadata(ClassMetadata $classMetadata): ValidationParams
	{
		$this->classMetadata = $classMetadata;
		return $this;
	}

	public function getProperty(): ReflectionProperty
	{
		return $this->property;
	}

	public function setProperty(ReflectionProperty $property): ValidationParams
	{
		$this->property = $property;
		return $this;
	}

	public function getValue(): mixed
	{
		return $this->value;
	}

	public function setValue(mixed $value): ValidationParams
	{
		$this->value = $value;
		return $this;
	}
}
