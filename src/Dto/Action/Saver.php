<?php
declare(strict_types=1);

namespace Common\Dto\Action;

use Common\Dto\BaseProvider;
use Common\Dto\BaseSaveData;
use Common\Dto\BaseSaveParams;
use Common\Dto\BaseSaver;
use Common\Dto\KeyConfig;
use Exception;
use Psr\Container\ContainerInterface;
use Throwable;

class Saver
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
	public function save(SaveParams $params): SaveResult
	{
		$actionResult = new SaveResult();
		$actionResult->setSuccess(false);

		$request = $params->getRequest();

		$body = json_decode($request->getContent(), true);

		if (!($dtoNamespace = $this->keyConfig->getNamespace($params->getDtoKey())))
		{
			throw new Exception('Could not find namespace');
		}

		/**
		 * @var BaseProvider $provider
		 */
		$provider = $this->container->get($dtoNamespace . '\Provider');

		if (
			($id = $params->getId())
			&& !($provider->byId($id))
		)
		{
			throw new Exception('Could not find dto by id');
		}

		try
		{
			/**
			 * @var BaseSaver $saver
			 */
			$saver = $this->container->get($dtoNamespace . '\Saver');

			$result = $saver->save(
				BaseSaveParams::create()
					->setDtoId($id)
					->setData(
						BaseSaveData::fromArray($body['data'])
					)
			);

			if (!$result->isSuccess())
			{
				$actionResult->setErrors($result->getErrors());

				return $actionResult;
			}

			$actionResult->setSuccess(true);
			$actionResult->setDto($result->getDto());
		}
		catch (Throwable $t)
		{
			throw $t;
		}

		return $actionResult;
	}
}
