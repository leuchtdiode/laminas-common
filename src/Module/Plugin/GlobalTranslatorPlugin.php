<?php
namespace Common\Module\Plugin;

use Common\Module\Plugin;
use Common\Translator;
use Laminas\I18n\Translator\TranslatorInterface;

class GlobalTranslatorPlugin implements Plugin
{
	private array $config;

	private TranslatorInterface $translator;

	public function __construct(array $config, TranslatorInterface $translator)
	{
		$this->config     = $config;
		$this->translator = $translator;
	}

	public function execute(): void
	{
		if (!$this->config['common']['translator']['global']['enabled'])
		{
			return;
		}

		Translator::setInstance($this->translator);

		setlocale(LC_TIME, $this->translator->getLocale());
	}
}
