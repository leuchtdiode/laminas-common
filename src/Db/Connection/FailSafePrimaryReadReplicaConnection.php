<?php
namespace Common\Db\Connection;

use Doctrine\DBAL\Connections\PrimaryReadReplicaConnection;
use Doctrine\DBAL\Driver\Connection;
use Doctrine\DBAL\Driver\Exception as DriverException;
use Exception;

class FailSafePrimaryReadReplicaConnection extends PrimaryReadReplicaConnection
{
	/**
	 * @param string $connectionName
	 * @return Connection
	 * @throws Exception if no host to connect found
	 * @throws DriverException
	 */
	protected function connectTo($connectionName): Connection
	{
		$params = $this->getParams();

		if ($connectionName === 'primary')
		{
			return parent::connectTo($connectionName);
		}

		$hosts   = $params['replica'];
		$hosts[] = $params['primary'];

		foreach ($hosts as $dbConfig)
		{
			try
			{
				return $this->_driver->connect($dbConfig);
			}
			catch (Exception $exception)
			{
				error_log($exception->getMessage());
			}
		}

		throw new Exception('Could not connect to any replica or primary');
	}
}
