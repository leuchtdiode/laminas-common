<?php
namespace Common\RequestData\Transformer;

use Exception;
use Common\RequestData\Transformer;

class Boolean implements Transformer
{
	/**
	 * @throws Exception
	 */
	public function transform(mixed $value): bool
	{
		if ($value === '0' || $value === 0 || $value === 'false' || $value === false)
		{
			return false;
		}

		if ($value === '1' || $value === 1 || $value === 'true' || $value === true)
		{
			return true;
		}

		throw new Exception('Invalid boolean value ' . $value . ' given');
	}
}