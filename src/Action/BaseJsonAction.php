<?php
namespace Common\Action;

use Laminas\View\Model\JsonModel;

abstract class BaseJsonAction extends BaseAction
{
	abstract public function executeAction(): JsonModel;
}