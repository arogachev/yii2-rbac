<?php

namespace arogachev\rbac\rules;

use yii\helpers\ArrayHelper;
use yii\rbac\Rule;

class CorrespondingUserRule extends Rule
{
    /**
     * @inheritdoc
     */
    public $name = 'correspondingUser';

    /**
     * @inheritdoc
     */
    public function execute($user, $item, $params)
    {
        $model = $params['model'];
        $attributeName = ArrayHelper::getValue($params, 'attribute', 'author_id');

        return $model->$attributeName == $user;
    }
}
