<?php
namespace Common\View\Helper;

use Common\Router\BaseUrlProvider;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class AbsoluteUrlFactory implements FactoryInterface
{
	/**
	 * @param ContainerInterface $container
	 * @param string $requestedName
	 * @param array|null $options
	 * @return AbsoluteUrl
	 * @throws ContainerExceptionInterface
	 * @throws NotFoundExceptionInterface
	 */
	public function __invoke(
		ContainerInterface $container,
		$requestedName,
		array $options = null
	)
	{
		return new AbsoluteUrl(
			$container->get(BaseUrlProvider::class)
		);
	}
}
