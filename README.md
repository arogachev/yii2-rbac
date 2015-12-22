# Yii 2 RBAC

RBAC management for Yii 2 framework.

The main purpose of this extension is to provide management of RBAC roles, permissions, rules
and relations between them through configuration arrays.

[![Latest Stable Version](https://poser.pugx.org/arogachev/yii2-rbac/v/stable)](https://packagist.org/packages/arogachev/yii2-rbac)
[![Total Downloads](https://poser.pugx.org/arogachev/yii2-rbac/downloads)](https://packagist.org/packages/arogachev/yii2-rbac)
[![Latest Unstable Version](https://poser.pugx.org/arogachev/yii2-rbac/v/unstable)](https://packagist.org/packages/arogachev/yii2-rbac)
[![License](https://poser.pugx.org/arogachev/yii2-rbac/license)](https://packagist.org/packages/arogachev/yii2-rbac)

- [Installation](#installation)
- [Features](#features)
- [Configuration arrays](#configuration-arrays)
- [Data synchronization](#data-synchronization)

## Installation

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist arogachev/yii2-rbac
```

or add

```
"arogachev/yii2-rbac": "*"
```

to the require section of your `composer.json` file.

## Features

- Adding new roles and permissions with descriptions
- Assigning rules to permissions
- Assigning permissions to roles
- Updating descriptions of roles and permissions

## Configuration arrays

First of all, you need to create three files for storing RBAC data:

- `roles.php`. Used for storing roles.
- `permissions.php`. Used for storing permissions and relations between permissions and rules.
- `children.php`. Used for storing relations between roles and permissions.

You can place it anywhere you want. If you are using advanced application, it's recommended to place them in
`common/rbac/data` folder.

Example of `roles.php` content:

```php
<?php
return [
    [
        'name' => 'default',
        'description' => 'Default',
    ],
    [
        'name' => 'admin',
        'description' => 'Administrator',
    ],
    [
        'name' => 'operator',
        'description' => 'Operator',
    ],
];
```

Both `name` and `description` are required for filling.

`default` is not required, but most of the times is needed because some permissions require check without assigning.
In this case make sure you have include it in your application config:

```php
'authManager' => [
    'class' => 'yii\rbac\DbManager',
    'defaultRoles' => ['default'],
],
```

Example of `permissions.php` content:

```php
<?php
return [
    [
        'name' => 'users.manage',
        'description' => 'Users management',
    ],
    [
        'name' => 'users.avatar.upload',
        'description' => 'Upload avatar for user',
        'rule' => 'arogachev\rbac\rules\CorrespondingUserRule',
    ],
    [
        'name' => 'users.avatar.upload.all',
        'description' => 'Upload avatar for any user',
    ],
    [
        'name' => 'users.password.change',
        'description' => 'Change password for user',
        'rule' => 'arogachev\rbac\rules\CorrespondingUserRule',
    ],
    [
        'name' => 'users.password.change.all',
        'description' => 'Change password for any user',
    ],
    [
        'name' => 'dispatching-room.access',
        'description' => 'Access to dispatching room',
    ],
    [
        'name' => 'settings.manage',
        'description' => 'Settings management',
    ],
    [
        'name' => 'sessions.access',
        'description' => 'Sessions management',
    ],
];
```

Both `name` and `description` are required for filling, `rule` is optional.

Example of `children.php` content:

```php
<?php
return [
    'admin' => [
        'users.manage',
        'users.avatar.upload.all',
        'users.password.change.all',
        'settings.manage',
        'sessions.access',
    ],
    'operator' => [
        'users.avatar.upload',
        'users.password.change',
        'dispatching-room.access',
        'chat.access',
    ],
];
```

## Data synchronization

To synchronize actual RBAC data with configuration arrays data add this to your console application config
(`config/console.php` for basic application and `console/config/main.php` for advanced application):

```php
'controllerMap' => [
    'rbac' => 'arogachev\rbac\controllers\RbacController',
],
```

Then you need to run command:

```
php yii rbac /path/to/your/config
```

Aliases are supported, so you can write something like `@common/rbac/data` as a parameter.
