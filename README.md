
A database component based on [meboo](https://github.com/catfan/Medoo), which can run in PHP FPM and [swoole](https://github.com/swoole/swoole-src) environment, and support database connection pool in swoole.

## Requirement
* PHP7.0+

* PDO extension installed.

* Swoole 4.4+

## Get Started

### Install 
```
$ composer require ziyoren/database
```

### update
```
$ composer update
```

### PDO Databases
```php
require 'vendor/autoload.php';

use ziyoren\Database\BaseModel; // swoole(支持数据库连接池)
//use ziyoren\Database\PDO;       // 传统的php-fpm 无连接池

//For database configuration, see ziyoren\Database\DbConfig.php
$db = new BaseModel(); //swoole模式下使用
//$db = new PDO(); //php-fpm模式下使用

$db->insert('account', [
    'user_name' => 'foo',
    'email' => 'foo@bar.com'
]);

$data = $db->select('account', [
    'user_name',
    'email'
], [
    'user_id' => 50
]);

echo json_encode($data);
```

### Redis pools
```php
require 'vendor/autoload.php';

use ziyoren\Database\BaseRedis;

//For Redis configuration, see /config/redis.php
$redis = new BaseRedis();
$redis->set('key', 'value');
$rst = $redis->get('key');

echo $rst; //value
```

## License

ziyoren/database is under the MIT license.

## Links
* Databases Doc: [https://medoo.in/doc](https://medoo.in/doc)
* Redis Doc: [https://github.com/phpredis/phpredis/blob/develop/README.markdown](https://medoo.in/doc)