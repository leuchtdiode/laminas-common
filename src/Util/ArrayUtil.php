<?php
declare(strict_types=1);

namespace Common\Util;

class ArrayUtil
{
	public static function firstOrNull(array $items): mixed
	{
		return $items
			? reset($items)
			: null;
	}

	/**
	 * @param string[] $values
	 * @return string[]
	 */
	public static function removeEmptyValues(array $values): array
	{
		return array_filter(
			$values,
			fn(?string $value) => !empty($value)
		);
	}

	public static function addIfNotIn(array &$arr, mixed $value): void
	{
		if (!in_array($value, $arr))
		{
			$arr[] = $value;
		}
	}

	/**
	 * @param string[] $values
	 * @return string[]
	 */
	public static function trim(array $values): array
	{
		return array_map(
			fn(string $value) => trim($value),
			$values
		);
	}
}
