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

use Medoo\Medoo;

class PDO extends Medoo
{
    public function __construct(array $options = [])
    {
        $options = $this->initOptions($options);
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
            throw new \RuntimeException('Databases config empty.');
        }
        return array_replace_recursive(DbConfig::getOptions(), $options);
    }
}