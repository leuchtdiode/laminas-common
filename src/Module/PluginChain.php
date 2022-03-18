<?php
namespace Common\Module;

class PluginChain
{
	/**
	 * @var Plugin[]
	 */
	private array $plugins = [];

	public static function create(): self
	{
		return new self();
	}

	public function addPlugin(Plugin $plugin): self
	{
		$this->plugins[] = $plugin;

		return $this;
	}

	public function executeAll(): void
	{
		foreach ($this->plugins as $plugin)
		{
			$plugin->execute();
		}
	}
}