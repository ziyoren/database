<?php
declare(strict_types=1);

/**
 * Redis connection pool component
 *
 * Need swoole extension support
 * swoole >=4.4
 *
 * Copyright 2020, Jianshan Liao
 * Released under the MIT license
 */

namespace ziyoren\Database;


class BaseRedis
{
    protected $pool;
    protected $redis;

    public function __construct(array $options = [])
    {
        $options = $this->initOptions($options);
        $this->pool = RedisPool::getInstance($options);
        $this->redis = $this->pool->get();
    }

    protected function initOptions($options)
    {
        if (empty($options)) {
            $config_path = CONF_PATH . 'redis.php';
            if (file_exists($config_path)) {
                $options = require $config_path;
            } else {
                throw new \RuntimeException('Redis config empty.');
            }
        }
        return $options;
    }

    public function __destruct()
    {
        $this->close();
    }

    public function __call($name, $arguments)
    {
        try {
            $data = $this->redis->{$name}(...$arguments);
            return $data;
        } catch (\RedisException $e) {
            throw $e;
        }
    }

    public function close()
    {
        $this->pool->close($this->redis);
    }

}