<?php
namespace CommonTest\Encryption;

use Common\Encryption\EncryptDecryptHandler;
use Common\Encryption\EncryptDecryptOptions;
use CommonTest\Base;
use Exception;

class EncryptDecryptHandlerTest extends Base
{
	/**
	 * @throws Exception
	 */
	public function test_encrypt_decrypt()
	{
		$handler = new EncryptDecryptHandler();

		$text = 'ich bin ein test';

		$options = EncryptDecryptOptions::create()
			->setKey('any-key')
			->setIv('any-iv');

		$encrypted = $handler->encrypt($text, $options);

		$this->assertEquals(
			'ich bin ein test',
			$handler->decrypt($encrypted, $options)
		);
	}
}
