<?php
declare(strict_types=1);

namespace Common\Dto;

class KeyConfig
{
	public function __construct(
		private readonly array $config
	)
	{
	}

	public function getNamespace(string $key): string
	{
		return $this->get($key, 'namespace');
	}

	public function getDbNamespace(string $key): string
	{
		return $this->get($key, 'dbNamespace');
	}

	private function get(string $key, string $property)
	{
		return $this->config['common']['dto'][$key][$property];
	}
}
