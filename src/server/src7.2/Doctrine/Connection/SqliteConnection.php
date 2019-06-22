<?php

namespace Nrg\Doctrine\Connection;

use Doctrine\Common\EventManager;
use Doctrine\DBAL\Configuration;
use Doctrine\DBAL\Connection as DbalConnection;
use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\Driver\PDOSqlite\Driver;
use Nrg\Doctrine\Abstraction\Connection;

class SqliteConnection extends DbalConnection implements Connection
{
    /**
     * @param Configuration|null $dbalConfig
     * @param EventManager|null $eventManager
     * @param array $config
     *
     * @throws DBALException
     */
    public function __construct(Configuration $dbalConfig = null, EventManager $eventManager = null, array $config = [])
    {
        $params = $config + ['driver' => 'pdo_sqlite'];

        parent::__construct($params, new Driver(), $dbalConfig, $eventManager);
    }
}
