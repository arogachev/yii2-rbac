<?php

defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'test');

require(__DIR__ . '/../vendor/autoload.php');
require(__DIR__ . '/../vendor/yiisoft/yii2/Yii.php');

$config = [
    'id' => 'unit',
    'basePath' => __DIR__,
    'aliases' => [
        'tests' => __DIR__,
        'vendor' => __DIR__ . '/../vendor',
    ],
    'components' => [
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
        ],
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=localhost;dbname=yii2_rbac_tests',
            'username' => 'tester',
            'password' => '',
        ]
    ],
];
new \yii\console\Application($config);