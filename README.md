
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

### php swoole (Support connection pool)
```php
require 'vendor/autoload.php';

use ziyoren\Database\BaseModel;

//For database configuration, see ziyoren\Database\DbConfig.php
$db = new BaseModel();

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

### php-fpm
```php
require 'vendor/autoload.php';

use ziyoren\Database\PDO;

//For database configuration, see ziyoren\Database\DbConfig.php
$db = new PDO();

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

## License

ziyoren/database is under the MIT license.

## Links
* Documentation: [https://medoo.in/doc](https://medoo.in/doc)