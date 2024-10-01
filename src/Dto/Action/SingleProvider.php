<?php
declare(strict_types=1);

namespace Common\Dto\Action;

use Common\Dto\BaseProvider;
use Common\Dto\CreateOptions\Generic;
use Common\Dto\CreateOptions\PropertyToIgnore;
use Common\Dto\CreateOptions\PropertyToLoad;
use Common\Dto\KeyConfig;
use Exception;
use Psr\Container\ContainerInterface;
use Throwable;

class SingleProvider
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
	public function get(SingleParams $params): SingleResult
	{
		$singleResult = new SingleResult();

		$getParams = $params->getGetParams();

		if (!($dtoNamespace = $this->keyConfig->getNamespace($params->getDtoKey())))
		{
			throw new Exception('Could not find namespace');
		}

		$propertiesToLoad = [];

		foreach (($getParams['propertiesToLoad'] ?? []) as $item)
		{
			$propertiesToLoad[] = PropertyToLoad::create()
				->setDtoKey($item['dtoKey'])
				->setProperty($item['property']);
		}

		$propertiesToIgnore = [];

		foreach (($getParams['propertiesToIgnore'] ?? []) as $item)
		{
			$propertiesToIgnore[] = PropertyToIgnore::create()
				->setDtoKey($item['dtoKey'])
				->setProperty($item['property']);
		}

		/**
		 * @var BaseProvider $provider
		 */
		$provider = $this->container->get($dtoNamespace . '\Provider');

		try
		{
			$dto = $provider->byId(
				$params->getDtoId(),
				Generic::create()
					->setPropertiesToLoad($propertiesToLoad)
					->setPropertiesToIgnore($propertiesToIgnore)
			);

			if (!$dto)
			{
				$singleResult->setErrors([

				]);

				return $singleResult;
			}

			$singleResult->setItem($dto);

			return $singleResult;
		}
		catch (Throwable $t)
		{
			throw $t;
		}
	}
}
