<?php
declare(strict_types=1);

/**
 * Database default configuration
 */

namespace ziyoren\Database;


class DbConfig
{

    public static function getOptions()
    {
        return [
            // required
            'database_type' => 'mysql',
            'database_name' => 'test',
            'server' => 'localhost',
            'username' => 'root',
            'password' => '',

            // [optional]
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_general_ci',
            'port' => 3306,

            // [optional] Table prefix
            'prefix' => '',

            // [optional] Enable logging (Logging is disabled by default for better performance)
            'logging' => false,

            // [optional] MySQL socket (shouldn't be used with server and port)
            'socket' => null, // '/tmp/mysql.sock',

            // [optional] driver_option for connection, read more from http://www.php.net/manual/en/pdo.setattribute.php
            // 'option' => [
            //     PDO::ATTR_CASE => PDO::CASE_NATURAL
            // ],
            'option' => [],

            // [optional] Medoo will execute those commands after connected to the database for initialization
            // 'command' => [
            //     'SET SQL_MODE=ANSI_QUOTES'
            // ],
            'command' => [],

            // [Pools] 连接池数量
            'size' => 64,
        ];
    }
}