<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model arogachev\rbac\models\AssignRoleToUserForm */
/* @var $form yii\widgets\ActiveForm */

$user = $model->getUser();
$this->title = Yii::t('rbac', 'Assigning role to user') . ': ' . $user->getFullName();
$this->params['breadcrumbs'][] = ['label' => Yii::t('rbac', 'Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = [
    'label' => $user->getFullName(),
    'url' => ['view', $user->getPrimaryKey()[0] => $user->primaryKey,
]];
$this->params['breadcrumbs'][] = Yii::t('rbac', 'Assigning role');
?>

<div class="user-assign-role-form">
    <h1><?= Html::encode($this->title) ?></h1>

    <div class="row">
        <div class="col-lg-5">
            <?= $this->render('_assign_role-form', ['model' => $model]) ?>
        </div>
    </div>
</div>
