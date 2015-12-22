<?php

use arogachev\rbac\components\Rbac;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model arogachev\rbac\models\AssignRoleToUserForm */
/* @var $form yii\widgets\ActiveForm */
?>

<?php $form = ActiveForm::begin(); ?>

<?= $form->field($model, 'role')->dropDownList(Rbac::getRolesMap(), [
    'prompt' => Yii::t('rbac', 'Without any roles'),
]) ?>

<div class="form-group">
    <?= Html::submitButton(Yii::t('rbac', 'Assign'), ['class' => 'btn btn-primary']) ?>
</div>

<?php ActiveForm::end(); ?>

