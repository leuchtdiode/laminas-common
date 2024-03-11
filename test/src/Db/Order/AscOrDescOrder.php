<?php

namespace CommonTest\Db\Order;

use Common\Db\Order\AscOrDesc;
use Override;

class AscOrDescOrder extends AscOrDesc
{
	#[Override] protected function getField(): string
	{
		return 't.irrelevant';
	}
}