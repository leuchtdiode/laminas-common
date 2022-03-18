<?php
namespace Common\Util;

class IpUtil
{
	public static function getIp(): ?string
	{
		$ip = null;

		foreach ([ 'HTTP_X_REAL_IP', 'REMOTE_ADDR' ] as $property)
		{
			$ip = filter_input(INPUT_SERVER, $property);

			if ($ip)
			{
				break;
			}
		}

		return $ip;
	}
}