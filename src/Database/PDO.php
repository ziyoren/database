<?php
declare(strict_types=1);

/**
 * Database components extended from medoo
 * Auto load framework configuration
 * It can be used in php-fpm mode
 * Version 0.0.1
 *
 * Copyright 2020, Jianshan Liao
 * Released under the MIT license
 */

namespace ziyoren\Database;

use RuntimeException;
use Medoo\Medoo;

class PDO extends Medoo
{
    protected $_prefix = '';

    public function __construct(array $options = [])
    {
        $options = $this->initOptions($options);
        if (isset($options[ 'prefix' ]))
        {
            $this->_prefix = $options[ 'prefix' ];
        }
        $this->_init();
        parent::__construct($options);
    }

    protected function initOptions($options)
    {
        if (empty($options)) {
            $config_path = CONF_PATH . 'databases.php';
            if (file_exists($config_path)) {
                $options = require $config_path;
            }
        }
        if (empty($options) || !is_array($options)){
            throw new RuntimeException('Databases config empty.');
        }
        return array_replace_recursive(DbConfig::getOptions(), $options);
    }

    public function prefix(): string
    {
        $this->prefix = $this->_prefix;
        return $this->prefix;
    }


    public function prefixEmpty()
    {
        $this->prefix = '';
    }


    protected function _init()
    {
        // initialization hook
    }


    public function beginTransaction(): bool
    {
        if ( $this->pdo->inTransaction() ) { //嵌套事务
            throw new RuntimeException('Do\'t support nested transaction.');
        }
        return $this->pdo->beginTransaction();
    }


    public function commit(): bool
    {
        return $this->pdo->commit();
    }


    public function rollBack(): bool
    {
        return $this->pdo->rollBack();
    }

}