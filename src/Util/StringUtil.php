<?php
namespace Common\Util;

class StringUtil
{
	public static function contains(string $string, string $searchString): bool
	{
		return str_contains($string, $searchString);
	}

	public static function startsWith(string $string, string $searchString): bool
	{
		return str_starts_with($string, $searchString);
	}

	public static function endsWith(string $string, string $searchString): bool
	{
		$length = strlen($searchString);

		return substr($string, -$length) === $searchString;
	}

	public static function compare(string $a, string $b): int
	{
		return strcasecmp($a, $b);
	}
}