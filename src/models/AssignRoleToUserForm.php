<?php

namespace arogachev\rbac\models;

use arogachev\rbac\components\Rbac;
use Yii;
use yii\base\Model;
use yii\i18n\PhpMessageSource;

class AssignRoleToUserForm extends Model
{
    /**
     * @var string Role name
     */
    public $role;

    /**
     * @var \yii\db\ActiveRecord
     */
    private $_user;

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->role = Rbac::getUserRoleName($this->_user->primaryKey);

        Yii::setAlias('@rbac', dirname(__DIR__));
        Yii::$app->i18n->translations['rbac'] = [
            'class' => PhpMessageSource::className(),
            'basePath' => '@rbac/messages',
        ];

        parent::init();
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['role', 'in', 'range' => Rbac::getRolesNames()],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'role' => Yii::t('rbac', 'Role'),
        ];
    }

    /**
     * @return boolean
     */
    public function assignRole()
    {
        $role = Yii::$app->authManager->getRole($this->role);

        Yii::$app->authManager->revokeAll($this->_user->primaryKey);

        if ($this->role) {
            Yii::$app->authManager->assign($role, $this->_user->primaryKey);
        }
    }

    /**
     * @return \yii\db\ActiveRecord
     */
    public function getUser()
    {
        return $this->_user;
    }

    /**
     * @param \yii\db\ActiveRecord $user
     */
    public function setUser($user)
    {
        $this->_user = $user;
    }
}
