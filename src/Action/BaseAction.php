<?php
namespace Common\Action;

use Laminas\Mvc\Controller\AbstractActionController;

abstract class BaseAction extends AbstractActionController
{
	abstract public function executeAction();
}
