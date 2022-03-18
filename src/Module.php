<?php
namespace Common;

use Common\Module\Plugin\GlobalTranslatorPlugin;
use Common\Module\PluginChain;
use Laminas\Mvc\MvcEvent;

class Module
{
	/**
	 * @return array
	 */
	public function getConfig(): array
	{
		return include __DIR__ . '/../config/module.config.php';
	}

	public function onBootstrap(MvcEvent $e)
	{
		$eventManager 	= $e->getApplication()->getEventManager();
		$serviceManager = $e->getApplication()->getServiceManager();

		$eventManager->attach(MvcEvent::EVENT_ROUTE, function() use ($serviceManager)
		{
			PluginChain::create()
				->addPlugin($serviceManager->get(GlobalTranslatorPlugin::class))
				->executeAll();
		});
	}
}
