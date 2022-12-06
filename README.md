<h1 align="center">TSA Document</h1>

### System requirements

php 7.4

php7.4-intl

mysql 5.7

### Installation

Copy of a remote repository:

~~~
git clone https://github.com/TSAdigital/tsa.document.git
~~~

Installing Composer Packages

~~~
composer install or composer update
~~~

### Database

Edit the file `config/db.php`:

```php
return [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=localhost;dbname=NAME_DB',
    'username' => 'USER_NAME_БД',
    'password' => 'PASSWORD_БД',
    'charset' => 'utf8',
];
```

### Config

Edit the file `config/web.php`:

Generate cookieValidationKey

```php
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'your key',
        ],
```
Email notifications

```php
'mailer' => [
            'class' => \yii\symfonymailer\Mailer::class,
            'viewPath' => '@app/mail',
                'transport' => [
                'scheme' => 'smtp',
                'host' => 'smtp.host.com',
                'username' => 'info@host.com',
                'password' => 'pass',
                'port' => 465,
            ],
            'useFileTransport' => false,
        ],
```

### Migrations
Application of migrations

~~~
yii migrate --migrationPath=@yii/rbac/migrations
yii rbac/init
yii migrate
~~~

### Log in to the system
Username/Password

~~~
admin/12345678
~~~
