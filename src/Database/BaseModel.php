<?php
declare(strict_types=1);

/**
 * Database connection pool component of Swoole
 * Version 1.0.0
 *
 * swoole >=4.4
 *
 * Copyright 2020, Jianshan Liao
 * Released under the MIT license
 */

namespace ziyoren\Database;


class BaseModel extends PDO
{
    protected $pool;

    public function __construct(array $options = [])
    {
        $options = $this->initOptions($options);

        if (isset($options['database_type'])) {
            $this->type = strtolower($options['database_type']);

            if ($this->type === 'mariadb') {
                $this->type = 'mysql';
            }
        }

        if (!in_array($this->type, \PDO::getAvailableDrivers())) {
            throw new \RuntimeException("Unsupported PDO driver: {$this->type}");
        }

        if (isset($options['prefix'])) {
            $this->prefix = $options['prefix'];
        }

        if (isset($options['logging']) && is_bool($options['logging'])) {
            $this->logging = $options['logging'];
        }

        $commands = (isset($options['command']) && is_array($options['command'])) ? $options['command'] : [];

        switch ($this->type) {
            case 'mysql':
                // Make MySQL using standard quoted identifier
                $commands[] = 'SET SQL_MODE=ANSI_QUOTES';

                break;

            case 'mssql':
                // Keep MSSQL QUOTED_IDENTIFIER is ON for standard quoting
                $commands[] = 'SET QUOTED_IDENTIFIER ON';

                // Make ANSI_NULLS is ON for NULL value
                $commands[] = 'SET ANSI_NULLS ON';

                break;
        }

        if (
            in_array($this->type, ['mysql', 'pgsql', 'sybase', 'mssql']) &&
            isset($options['charset'])
        ) {
            $commands[] = "SET NAMES '{$options[ 'charset' ]}'" . (
                $this->type === 'mysql' && isset($options['collation']) ?
                    " COLLATE '{$options[ 'collation' ]}'" : ''
                );
        }

        $this->pool = PDOPool::getInstance($options);
        $this->pdo = $this->pool->get();
        $this->dsn = PDOPool::dsn();

        foreach ($commands as $value) {
            $this->pdo->exec($value);
        }

    }

    public function __destruct()
    {
        $this->close();
    }

    public function close(){
        $this->pool->close($this->pdo);
    }
}