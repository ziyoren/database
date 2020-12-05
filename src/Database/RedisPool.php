<?php
declare(strict_types=1);

/**
 * Redis connection pool component of Swoole
 *
 * Need swoole extension support
 * swoole >=4.4
 *
 * Copyright 2020, Jianshan Liao
 * Released under the MIT license
 */

namespace ziyoren\Database;

use Swoole\Database\RedisConfig;
use Swoole\Database\RedisPool as SwooleRedisPool;
use Swoole\Runtime;

class RedisPool
{
    protected static $pools;
    private static $instance;

    protected $config = [
        'host' => '127.0.0.1',
        'port' => 6379,
        'timeout' => 0.0,
        'reserved' => '',
        'auth' => '',
        'db_index' => 0,
        'read_timeout' => 0.0,
        'retry_interval' => 0,
        'size' => 64,
    ];


    private function __construct(array $config){
        $this->config = array_replace_recursive($this->config, $config);
        $RedisConfig = new RedisConfig();
        $RedisConfig->withHost($this->config['host'])
            ->withPort($this->config['port'])
            ->withAuth($this->config['auth'])
            ->withDbIndex($this->config['db_index'])
            ->withTimeout($this->config['timeout']);
        self::$pools = new SwooleRedisPool($RedisConfig, $this->config['size']);
    }

    public static function getInstance($config)
    {
        if (empty(self::$instance)) {
            if (empty($config)) {
                throw new RuntimeException('Redis config empty');
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

}