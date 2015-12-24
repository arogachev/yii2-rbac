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
- [Rules](#rules)
- [GUI](#gui)

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
    'default' => [
        'users.password.change',
    ],
    'admin' => [
        'users.manage',
        'users.avatar.upload.all',
        'users.password.change.all',
        'settings.manage',
        'sessions.access',
    ],
    'operator' => [
        'users.avatar.upload',
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
    'rbac' => [
        'class' => 'arogachev\rbac\controllers\RbacController',
        'parserOptions' => [
            'configPath' => '@common/rbac/data',
        ],
    ],
],
```

Then you need to run command:

```
php yii rbac
```

List of available options in `parserOptions`:

- `$configPath` - full path to folder with config files. Aliases are supported. Required for filling.

## Rules

Extension provides `arogachev\rbac\rules\CorrespondingUserRule` that can be used to only allow
user to edit his own posts, etc. It's similar to `AuthorRule` described in official docs [here](http://www.yiiframework.com/doc-2.0/guide-security-authorization.html#using-rules).
You can attach it to permission as shown above, and use it in action as follows:

```php
/**
 * @param integer $id
 * @return string|\yii\web\Response
 * @throws BadRequestHttpException
 * @throws NotFoundHttpException
 */
public function actionUploadAvatar($id)
{
    $model = $this->findModel($id);
    if (!Yii::$app->user->can('users.avatar.upload.all') && !Yii::$app->user->can('users.avatar.upload', [
        'model' => $model,
        'attribute' => 'id',
    ])) {
        throw new BadRequestHttpException('You are not allowed to upload avatar for this user.');
    }

    ...
}
```

Use the related permission after the model was found.

Available params:

- `$model` - Model used for checking. Required for filling.
- `attribute` - The attribute name containing user id. Defaults to `author_id`.

In case of using advanced application it's recommended to place common rules like that in `common/rbac/rules`.
More specific rules can be placed inside of according modules.

## GUI

You can use `AssignRoleToUserForm` for assigning role to user.
Example of action (you can place it in `UsersController`):

```php
use arogachev\rbac\models\AssignRoleToUserForm;

...

/**
 * Assign RBAC role to user
 * @param integer $id
 * @return string|\yii\web\Response
 * @throws NotFoundHttpException
 */
public function actionAssignRole($id)
{
    $user = $this->findModel($id);
    $model = new AssignRoleToUserForm(['user' => $user]);

    if ($model->load(Yii::$app->request->post()) && $model->validate()) {
        $model->assignRole();

        return $this->redirect('index');
    }

    return $this->render('@rbac/views/users/assign-role', ['model' => $model]);
}
```

There are also `assign-role` and `_assign-role-form` (partial) views that you can use. It's for Bootstrap,
if it don't fit your needs you can copy and modify how you want, it's just a template.

To create a link for that action, most of the times, extending `GridView` `ActionColumn` is enough:

```php
[
    'class' => ActionColumn::className(),
    'template' => '{view} {update} {assign-role} {delete}',
    'buttons' => [
        'assign-role' => function ($url, $model, $key) {
            return Html::a('<span class="glyphicon glyphicon-link"></span>', $url, [
                'title' => 'Assign role',
                'aria-label' => 'Assign role',
                'data-pjax' => '0',
            ]);
        },
    ],
],
```
