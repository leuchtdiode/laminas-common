<?php
declare(strict_types=1);

namespace Common\Dto\Save;

use Carbon\CarbonImmutable;
use Common\RequestData\Transformer\Boolean;
use DateTime;
use DateTimeImmutable;
use Throwable;

class Transformer
{
	const DATE_TYPES = [
		'carbondate',
		'carbondate_immutable',
		'date',
		'date_immutable',
	];

	const DATE_TYPES_VANILLA = [
		'date',
		'date_immutable',
	];

	const DATE_TYPES_CARBON = [
		'date',
		'date_immutable',
	];

	const DATETIME_TYPES = [
		'carbondatetime',
		'carbondatetime_immutable',
		'datetime',
		'datetime_immutable',
	];

	const DATETIME_TYPES_VANILLA = [
		'datetime',
		'datetime_immutable',
	];

	const DATETIME_TYPES_CARBON = [
		'carbondatetime',
		'carbondatetime_immutable',
	];

	public function transformPreValidation(TransformParams $params): mixed
	{
		$classMetadata = $params->getClassMetadata();
		$property      = $params->getProperty();
		$value         = $params->getValue();

		if ($classMetadata->hasField($property->getName()))
		{
			$fieldMapping = $classMetadata->getFieldMapping($property->getName());

			$fieldType = $fieldMapping['type'] ?? null;

			if (
				($value instanceof DateTime || $value instanceof DateTimeImmutable)
				&& in_array($fieldType, self::DATE_TYPES)
			)
			{
				$value = $value->format('Y-m-d');
			}

			if (
				($value instanceof DateTime || $value instanceof DateTimeImmutable)
				&& in_array($fieldType, self::DATETIME_TYPES)
			)
			{
				$value = $value->format('Y-m-d H:i:s');
			}
		}

		return $value;
	}

	/**
	 * @throws Throwable
	 */
	public function transform(TransformParams $params): mixed
	{
		$classMetadata = $params->getClassMetadata();
		$property      = $params->getProperty();
		$value         = $params->getValue();

		if ($classMetadata->hasField($property->getName()))
		{
			$fieldMapping = $classMetadata->getFieldMapping($property->getName());

			$fieldType = $fieldMapping['type'] ?? null;

			if ($value && $fieldType === 'boolean')
			{
				$value = (new Boolean())->transform($value);
			}

			if ($value && in_array($fieldType, array_merge(self::DATE_TYPES_CARBON, self::DATETIME_TYPES_CARBON)))
			{
				$value = CarbonImmutable::parse($value);
			}

			if ($value && in_array($fieldType, array_merge(self::DATE_TYPES_VANILLA, self::DATETIME_TYPES_VANILLA)))
			{
				$value = new DateTime($value);
			}
		}

		return $value;
	}
}
