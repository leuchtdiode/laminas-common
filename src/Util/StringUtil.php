<?php
namespace Common\Util;

class StringUtil
{
	public static function contains($string, $searchString): bool
	{
		return str_contains($string, $searchString);
	}

	public static function startsWith($string, $searchString): bool
	{
		return str_starts_with($string, $searchString);
	}

	/**
	 * @param $string
	 * @param $searchString
	 * @return bool
	 */
	public static function endsWith($string, $searchString): bool
	{
		$length = strlen($searchString);

		return substr($string, -$length) === $searchString;
	}

	/**
	 * @param string $a
	 * @param string $b
	 * @return int
	 */
	public static function compare($a, $b): int
	{
		return strcasecmp($a, $b);
	}
}