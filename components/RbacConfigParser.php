<?php

namespace arogachev\rbac\components;

use Yii;
use yii\base\InvalidConfigException;
use yii\base\Object;

class RbacConfigParser extends Object
{
    /**
     * @var string
     */
    public $configPath;

    /**
     * @var \yii\rbac\BaseManager
     */
    protected $authManager;

    /**
     * @inheritdoc
     * @throws InvalidConfigException
     */
    public function init()
    {
        if (!$this->configPath) {
            throw new InvalidConfigException('$configPath is required.');
        }

        $this->configPath = Yii::getAlias($this->configPath);
        $this->authManager = Yii::$app->authManager;

        parent::init();
    }

    /**
     * Fill data from configuration files
     */
    public function fill()
    {
        $this->fillRoles();
        $this->fillPermissions();
        $this->fillChildren();
    }

    /**
     * Get configuration as array by given name
     * @param string $name
     * @return array
     */
    protected function getConfig($name)
    {
        return include "$this->configPath/$name.php";
    }

    /**
     * Fill roles from config
     */
    protected function fillRoles()
    {
        foreach ($this->getConfig('roles') as $roleConfig) {
            $existingRole = $this->authManager->getRole($roleConfig['name']);
            if ($existingRole) {
                if ($roleConfig['description'] == $existingRole->description) {
                    continue;
                }

                $existingRole->description = $roleConfig['description'];
                $this->authManager->update($roleConfig['name'], $existingRole);
            } else {
                $role = $this->authManager->createRole($roleConfig['name']);
                $role->description = $roleConfig['description'];
                $this->authManager->add($role);
            }
        }
    }

    /**
     * Fill permissions from config
     */
    protected function fillPermissions()
    {
        foreach ($this->getConfig('permissions') as $permissionConfig) {
            $existingPermission = $this->authManager->getPermission($permissionConfig['name']);
            if ($existingPermission) {
                $permission = $existingPermission;
                $permission->description = $permissionConfig['description'];
                $this->authManager->update($permissionConfig['name'], $permission);
            } else {
                $permission = $this->authManager->createPermission($permissionConfig['name']);
                $permission->description = $permissionConfig['description'];
                $this->authManager->add($permission);
            }

            if (isset($permissionConfig['rule'])) {
                /* @var $rule \yii\rbac\Rule */
                $rule = new $permissionConfig['rule'];
                $existingRule = $this->authManager->getRule($rule->name);

                if (!$permission->ruleName && !$existingRule) {
                    $this->authManager->add($rule);
                }

                $permission->ruleName = $rule->name;
                $this->authManager->update($permissionConfig['name'], $permission);
            }
        }
    }

    /**
     * Fill relations between roles and permissions
     */
    protected function fillChildren()
    {
        foreach ($this->getConfig('children') as $roleName => $permissionsNames) {
            $role = $this->authManager->getRole($roleName);
            foreach ($permissionsNames as $permissionName) {
                $permission = $this->authManager->getPermission($permissionName);
                if (!$this->authManager->hasChild($role, $permission)) {
                    $this->authManager->addChild($role, $permission);
                }
            }
        }
    }
}
