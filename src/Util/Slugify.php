<?php
declare(strict_types=1);

namespace Common\Util;

class Slugify
{
	public static function slugify(string $value): string
	{
		$value = str_replace(
			[
				'&',
				'ß',
				'Ü',
				'Ö',
				'Ä',
				'ü',
				'ö',
				'ä',
			],
			[
				'und',
				'ss',
				'Ue',
				'Oe',
				'Ae',
				'ue',
				'oe',
				'ae',
			],
			$value
		);

		// replace non letter or digits by -
		$value = preg_replace('~[^\pL\d]+~u', '-', $value);

		// transliterate
		$value = iconv('utf-8', 'us-ascii//TRANSLIT', $value);

		// remove unwanted characters
		$value = preg_replace('~[^-\w]+~', '', $value);

		// trim
		$value = trim($value, '-');

		// remove duplicate -
		$value = preg_replace('~-+~', '-', $value);

		return strtolower($value);
	}
}
