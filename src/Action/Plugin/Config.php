<?php
namespace Common\Action\Plugin;

use Laminas\Mvc\Controller\Plugin\AbstractPlugin;

class Config extends AbstractPlugin
{
	private array $config;

	public function __construct(array $config)
	{
		$this->config = $config;
	}

	public function __invoke(): array
	{
		return $this->config;
	}
}
