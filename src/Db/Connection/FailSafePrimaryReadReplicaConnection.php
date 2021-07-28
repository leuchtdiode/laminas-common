<?php
namespace Common\Db\Connection;

use Doctrine\DBAL\Connections\PrimaryReadReplicaConnection;
use Doctrine\DBAL\Driver\Connection;
use Exception;

class FailSafePrimaryReadReplicaConnection extends PrimaryReadReplicaConnection
{
	/**
	 * @param string $connectionName
	 * @return Connection
	 * @throws Exception if no host to connect found
	 */
	protected function connectTo($connectionName)
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
				return $this->_driver->connect(
					$dbConfig,
					$dbConfig['user'],
					$dbConfig['password'],
					$params['driverOptions'] ?? []
				);
			}
			catch (Exception $exception)
			{
				error_log($exception->getMessage());
			}
		}

		throw new Exception('Could not connect to any replica or primary');
	}
}
