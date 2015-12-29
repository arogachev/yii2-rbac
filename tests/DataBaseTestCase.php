<?php

namespace tests;

use Yii;


abstract class DataBaseTestCase extends \PHPUnit_Extensions_Database_TestCase
{
    /**
     * @inheritdoc
     */
    public function getConnection()
    {
        return $this->createDefaultDBConnection(Yii::$app->db->pdo);
    }

    /**
     * @inheritdoc
     */
    public static function setUpBeforeClass()
    {
        Yii::$app->db->open();

        $dump = file_get_contents(Yii::getAlias('@vendor') . '/yiisoft/yii2/rbac/migrations/schema-mysql.sql');
        $rows = array_filter(explode(';', $dump), function ($row) {
            return trim($row) !== '';
        });

        foreach ($rows as $sql) {
            Yii::$app->db->createCommand($sql)->execute();
        }
    }
}
