Расширения для синхронизации пользователей ИАС Мониторинг
=========================================================
Расширение для синхронизации пользователей ИАС Мониторинг (для подсистем программного комплекса))

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist budanoff/yii2-synch-user "*"
```

or add

```
"budanoff/yii2-synch-user": "*"
```

to the require section of your `composer.json` file.


Usage
-----

Once the extension is installed, simply use it in your code by  :

```php
<?= \budanoff\synchuser\AutoloadExample::widget(); ?>```