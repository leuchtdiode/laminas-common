<?php
namespace Common\View\Helper;

use Common\Router\BaseUrlProvider;
use Common\Util\StringUtil;
use Exception;
use Laminas\View\Helper\AbstractHelper;

class AbsoluteUrl extends AbstractHelper
{
	private BaseUrlProvider $baseUrlProvider;

	public function __construct(BaseUrlProvider $baseUrlProvider)
	{
		$this->baseUrlProvider = $baseUrlProvider;
	}

	/**
	 * @throws Exception
	 */
	public function __invoke(string $requestUri = ''): string
	{
		if (StringUtil::startsWith($requestUri, '/'))
		{
			$requestUri = substr($requestUri, 1);
		}

		return sprintf(
			'%s/%s',
			$this->baseUrlProvider->get(),
			$requestUri
		);
	}
}
