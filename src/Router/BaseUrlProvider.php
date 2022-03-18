<?php
namespace Common\Router;

use Exception;

class BaseUrlProvider
{
	private array $config;

	public function __construct(array $config)
	{
		$this->config = $config;
	}

	/**
	 * @throws Exception
	 */
	public function get(): string
	{
		$config = $this->config['common']['url'] ?? null;

		if (!$config)
		{
			throw new Exception('Could not find "url" config');
		}

		return sprintf(
			'%s://%s',
			$config['protocol'],
			$config['host']
		);
	}
}