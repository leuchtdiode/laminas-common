<?php
namespace Common\Hydration;

use Common\Util\StringUtil;
use DateTime;
use DateTimeImmutable;
use Doctrine\Common\Collections\Collection;
use Exception;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;
use UnitEnum;

class ObjectToArrayHydrator
{
	const OBJECT_TO_ARRAY_HYDRATOR_PROPERTY = '@ObjectToArrayHydratorProperty';

	/**
	 * @param $arrayOrObject
	 * @return array
	 * @throws Exception
	 */
	public static function hydrate($arrayOrObject): mixed
	{
		try
		{
			if (is_array($arrayOrObject))
			{
				return self::hydrateFromArray($arrayOrObject);
			}

			return self::hydrateFromObject($arrayOrObject);
		}
		catch (Exception $ex)
		{
			error_log($ex->getMessage());

			throw $ex;
		}
	}

	/**
	 * @param $object
	 * @return array|null|string
	 * @throws ReflectionException
	 */
	private static function hydrateFromObject($object): mixed
	{
		if ($object instanceof DateTime || $object instanceof DateTimeImmutable)
		{
			return $object->format('c');
		}

		if ($object instanceof UnitEnum && property_exists($object, 'value'))
		{
			return $object->value;
		}

		if (!$object instanceof ArrayHydratable)
		{
			if ($object && method_exists($object, '__toString'))
			{
				return (string)$object;
			}

			return null;
		}

		$reflection = new ReflectionClass(get_class($object));

		$asArray = [];

		foreach ($reflection->getMethods() as $method)
		{
			$methodName = $method->getName();

			if (!self::methodNameAllowed($methodName))
			{
				continue;
			}

			if (!self::isAllowedProperty($reflection, $method))
			{
				continue;
			}

			$value = self::handleValue($method->invoke($object));

			$asArray[self::correctMethodName($methodName)] = $value;
		}

		$hydrationConfigAttribute = $reflection->getAttributes(Hydration::class)[0] ?? null;

		if ($hydrationConfigAttribute && method_exists($object, 'getData'))
		{
			$excludedProperties = $hydrationConfigAttribute->getArguments()['excludedProperties'] ?? [];

			foreach ($object->getData() as $property => $value)
			{
				if (in_array($property, $excludedProperties))
				{
					continue;
				}

				$asArray[$property] = self::handleValue($value);
			}
		}

		return $asArray;
	}

	private static function handleValue(mixed $value): mixed
	{
		if (is_object($value))
		{
			if ($value instanceof Collection)
			{
				$value = self::hydrateFromArray($value);
			}
			else
			{
				$value = self::hydrateFromObject($value);
			}
		}
		else
		{
			if (is_array($value))
			{
				$value = self::hydrateFromArray($value);
			}
		}

		return $value;
	}

	/**
	 * @param $array
	 * @throws ReflectionException
	 */
	private static function hydrateFromArray($array): array
	{
		$values = [];

		foreach ($array as $item)
		{
			if (is_object($item))
			{
				$values[] = self::hydrateFromObject($item);
			}
			else
			{
				$values[] = $item;
			}
		}

		return $values;
	}

	private static function correctMethodName(string $methodName): string
	{
		$firstCharCount = 3;

		if (StringUtil::startsWith($methodName, 'is'))
		{
			$firstCharCount = 2;
		}

		return lcfirst(substr($methodName, $firstCharCount));
	}

	private static function methodNameAllowed($methodName): bool
	{
		return StringUtil::startsWith($methodName, 'get')
			|| StringUtil::startsWith($methodName, 'is');
	}

	private static function isAllowedProperty(ReflectionClass $reflectionClass, ReflectionMethod $method): bool
	{
		try
		{
			if ($method->getAttributes(ObjectToArrayHydratorProperty::class))
			{
				return true;
			}

			// check if method has necessary doc comment (legacy)
			if (StringUtil::contains($method->getDocComment(), self::OBJECT_TO_ARRAY_HYDRATOR_PROPERTY))
			{
				return true;
			}

			$cutPosition = StringUtil::startsWith($method->getName(), 'get')
				? 3
				: 2;

			// if method does not, check if property has doc comment
			$propertyName = lcfirst(
				substr($method->getName(), $cutPosition)
			);

			$property = $reflectionClass->getProperty($propertyName);

			if ($property->getAttributes(ObjectToArrayHydratorProperty::class))
			{
				return true;
			}

			if (StringUtil::contains($property->getDocComment(), self::OBJECT_TO_ARRAY_HYDRATOR_PROPERTY))
			{
				return true;
			}
		}
		catch (Exception $ex)
		{
			// do nothing, probably because property does not exist
		}

		return false;
	}
}
