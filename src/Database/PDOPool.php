<?php
declare(strict_types=1);

/**
 * Database connection pool component of Swoole
 *
 * Need swoole extension support
 * swoole >=4.4
 *
 * Copyright 2020, Jianshan Liao
 * Released under the MIT license
 */

namespace ziyoren\Database;

use RuntimeException;
use Swoole\Database\PDOConfig;
use Swoole\Database\PDOPool as SwoolePDOPool;

class PDOPool
{
    protected static $pools;
    private static $instance;

    protected $config = [];

    protected static $PDOConfig;

    private function __construct(array $config)
    {
        if (empty(self::$pools)) {
            $this->config = array_replace_recursive(DbConfig::getOptions(), $config);
            $pdo_config = new PDOConfig();
            $pdo_config->withHost($this->config['server'])
                ->withDriver($this->config['database_type'])
                ->withPort($this->config['port'])
                ->withUnixSocket($this->config['socket'])
                ->withDbName($this->config['database_name'])
                ->withCharset($this->config['charset'])
                ->withUsername($this->config['username'])
                ->withPassword($this->config['password'])
                ->withOptions($this->config['option'] ?? []);
            self::$PDOConfig = $pdo_config;
            self::$pools = new SwoolePDOPool($pdo_config, $this->config['size']);
        }
    }

    public static function getInstance($config)
    {
        if (empty(self::$instance)) {
            if (empty($config)) {
                throw new RuntimeException('pdo config empty');
            }
            self::$instance = new static($config);
        }

        return self::$instance;
    }


    public function get()
    {
        return self::$pools->get();
    }

    public function close($connection = null)
    {
        self::$pools->put($connection);
    }

    public static function dsn()
    {
        $config = self::$PDOConfig;
        return "{$config->getDriver()}:" .
            (
            $config->hasUnixSocket() ?
                "unix_socket={$config->getUnixSocket()};" :
                "host={$config->getHost()};" . "port={$config->getPort()};"
            ) .
            "dbname={$config->getDbname()};" .
            "charset={$config->getCharset()}";
    }

}