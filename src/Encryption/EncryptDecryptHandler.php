<?php
namespace Common\Encryption;

use Exception;
use function extension_loaded;

class EncryptDecryptHandler
{
	const ENCRYPT_METHOD = 'AES-256-CBC';

	private string $key;

	private string $iv;

	/**
	 * @param $text
	 * @throws Exception if openssl extension is missing
	 */
	public function encrypt($text, EncryptDecryptOptions $options): string
	{
		$this->prepare($options);

		$encrypted = openssl_encrypt(
			$text,
			self::ENCRYPT_METHOD,
			$this->key,
			0,
			$this->iv
		);

		return base64_encode($encrypted);
	}

	/**
	 * @param $text
	 * @param EncryptDecryptOptions $options
	 * @throws Exception if openssl extension is missing
	 */
	public function decrypt($text, EncryptDecryptOptions $options): string|false
	{
		$this->prepare($options);

		return openssl_decrypt(
			base64_decode($text),
			self::ENCRYPT_METHOD,
			$this->key,
			0,
			$this->iv
		);
	}

	/**
	 * @throws Exception
	 */
	private function prepare(EncryptDecryptOptions $options): void
	{
		if (!extension_loaded('openssl'))
		{
			throw new Exception('Extension openssl is mandatory to use this handler');
		}

		// hash
		$this->key = hash('sha256', $options->getKey());

		// iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
		$this->iv = substr(hash('sha256', $options->getIv()), 0, 16);
	}
}