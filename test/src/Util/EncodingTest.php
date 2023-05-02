<?php
declare(strict_types=1);

namespace CommonTest\Util;

use Common\Util\Encoding;
use CommonTest\Base;

class EncodingTest extends Base
{
	public function test_encode(): void
	{
		$textToTest = 'töst';

		$text = Encoding::utf8Decode($textToTest);
		$text = Encoding::utf8Encode($text);

		$this->assertEquals('töst', $text);
	}

	public function test_decode(): void
	{
		$textToTest = 'töst';

		$text = Encoding::utf8Encode($textToTest);
		$text = Encoding::utf8Decode($text);

		$this->assertEquals('töst', $text);
	}
}