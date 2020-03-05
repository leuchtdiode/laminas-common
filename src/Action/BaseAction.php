<?php
namespace Common\Action;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\JsonModel;

abstract class BaseAction extends AbstractActionController
{
	/**
	 * @return JsonModel
	 */
	abstract public function executeAction();
}
