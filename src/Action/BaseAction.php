<?php
namespace Common\Action;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\JsonModel;

abstract class BaseAction extends AbstractActionController
{
	abstract public function executeAction(): JsonModel;
}
