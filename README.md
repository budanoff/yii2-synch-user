Расширения для синхронизации пользователей ИАС Мониторинг
=========================================================
Расширение для синхронизации пользователей ИАС Мониторинг (для подсистем программного комплекса))

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist budanoff/yii2-synch-user:dev-master
```


to the require section of your `composer.json` file.


Usage
-----
update config file web.php:

```php
    'modules'=>[
            ...
            'synchuser' => [
                'class' => 'budanoff\synchuser\Module',
                'secret_key' => ''//insert secret key
            ],
            ...
        ],
```
Make the route public:
```php
    'as access' => [
            'class' => '',
            'allowActions' => [
                ...
                'synchuser/*'
            ]
        ],
```
You can customize roles for user. At block 'synchuser' add array like this:
```php
'role' => [
                "podved" => "user",
                "other_podved" => "user",
                "dep10" => [
                    "dep10",
                    "user"
                ]
            ]
```
 for reload role of user everytime:
 ```php
'reload_role' => true,
```