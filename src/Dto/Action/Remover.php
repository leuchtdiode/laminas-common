<?php
declare(strict_types=1);

namespace Common\Dto\Action;

use Common\Dto\BaseProvider;
use Common\Dto\BaseRemover;
use Common\Dto\KeyConfig;
use Exception;
use Psr\Container\ContainerInterface;
use Common\Dto\BaseRemoveParams;
use Throwable;

class Remover
{
	public function __construct(
		private readonly ContainerInterface $container,
		private readonly KeyConfig $keyConfig
	)
	{
	}

	/**
	 * @throws Throwable
	 */
	public function remove(RemoveParams $params): void
	{
		if (!($dtoNamespace = $this->keyConfig->getNamespace($params->getDtoKey())))
		{
			throw new Exception('Could not find namespace');
		}

		/**
		 * @var BaseProvider $provider
		 */
		$provider = $this->container->get($dtoNamespace . '\Provider');

		if (!($dto = $provider->byId($params->getId())))
		{
			throw new Exception('Could not find dto by id');
		}

		try
		{
			/**
			 * @var BaseRemover $remover
			 */
			$remover = $this->container->get($dtoNamespace . '\Remover');

			$remover->remove(
				BaseRemoveParams::create()
					->setDto($dto)
			);

			return;
		}
		catch (Throwable $t)
		{
			throw $t;
		}
	}
}
