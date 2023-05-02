<?php
declare(strict_types=1);

namespace Common\Util;

class Encoding
{
	public static function utf8Decode(string $text, string $toEncoding = 'ISO-8859-1'): string
	{
		return mb_convert_encoding($text, $toEncoding, 'UTF-8');
	}

	public static function utf8Encode(string $text, string $fromEncoding = 'ISO-8859-1'): string
	{
		return mb_convert_encoding($text, 'UTF-8', $fromEncoding);
	}
}