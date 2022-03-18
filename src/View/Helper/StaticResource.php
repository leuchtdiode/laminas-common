<?php
namespace Common\View\Helper;

use Laminas\View\Helper\AbstractHelper;

class StaticResource extends AbstractHelper
{
	const NAME = 'staticResource';

	private string $basePath;

	public function __construct(string $basePath)
	{
		$this->basePath = $basePath;
	}

	public function __invoke($resource): string
	{
		$file = $this->basePath . DIRECTORY_SEPARATOR . $resource;

		$pathInfo = pathinfo($file);

		return sprintf(
			'%s/%s__%s.%s',
			str_replace($this->basePath, '', $pathInfo['dirname']),
			$pathInfo['filename'],
			filemtime($file),
			$pathInfo['extension']
		);
	}
}
