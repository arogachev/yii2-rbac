<?php

namespace arogachev\rbac\components;

use Yii;
use yii\helpers\ArrayHelper;

class Rbac
{
    /**
     * Get roles (without default roles)
     * @return \yii\rbac\Role[]
     */
    public static function getNotDefaultRoles()
    {
        /* @var $authManager \yii\rbac\BaseManager */
        $authManager = Yii::$app->authManager;
        $roles = $authManager->getRoles();
        foreach ($authManager->defaultRoles as $defaultRole) {
            unset($roles[$defaultRole]);
        }

        return $roles;
    }

    /**
     * Get roles names list (without default roles)
     * @return array
     */
    public static function getRolesNames()
    {
        return ArrayHelper::getColumn(static::getNotDefaultRoles(), 'name');
    }

    /**
     * Get roles names list with descriptions (without default roles)
     * @return array
     */
    public static function getRolesMap()
    {
        return ArrayHelper::map(static::getNotDefaultRoles(), 'name', 'description');
    }

    /**
     * Get text name of user role
     * @param integer $userId
     * @return string|null
     */
    public static function getUserRoleName($userId)
    {
        $roles = Yii::$app->authManager->getRolesByUser($userId);
        if (!$roles) {
            return null;
        }

        /* @var $role \yii\rbac\Role */
        $role = reset($roles);

        return $role->name;
    }
}
