<?php

namespace tests;

use arogachev\rbac\components\RbacConfigParser;


class RbacUpdateTest extends DataBaseTestCase
{
    /**
     * @inheritdoc
     */
    protected function getDataSet()
    {
        return $this->createXMLDataSet(__DIR__ . '/data/init.xml');
    }

    public function testUdpate()
    {
        $options = [
            'configPath' => '@tests/rbac/update',
        ];
        $parser = new RbacConfigParser($options);
        $parser->fill();

        $dataSet = $this->createXMLDataSet(__DIR__ . '/data/test-update.xml');
        $authItemTable = $this->getConnection()->createQueryTable(
            'auth_item',
            'SELECT `name`, `type`, `description`, `rule_name` FROM `auth_item`'
        );
        $this->assertTablesEqual($dataSet->getTable('auth_item'), $authItemTable);

        $authItemChildTable = $this->getConnection()->createQueryTable(
            'auth_item_child',
            'SELECT * FROM `auth_item_child`'
        );
        $this->assertTablesEqual($dataSet->getTable('auth_item_child'), $authItemChildTable);

        $authRuleTable = $this->getConnection()->createQueryTable(
            'auth_rule',
            'SELECT `name` FROM `auth_rule`'
        );
        $this->assertTablesEqual($dataSet->getTable('auth_rule'), $authRuleTable);
    }

}
