<?php
namespace Common\Encryption;

class EncryptDecryptOptions
{
	private string $key;

	private string $iv;

	public static function create(): self
	{
		return new self();
	}

	public function getKey(): string
	{
		return $this->key;
	}

	public function setKey(string $key): EncryptDecryptOptions
	{
		$this->key = $key;
		return $this;
	}

	public function getIv(): string
	{
		return $this->iv;
	}

	public function setIv(string $iv): EncryptDecryptOptions
	{
		$this->iv = $iv;
		return $this;
	}
}