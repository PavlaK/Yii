<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\AccountForm */

$this->title = Yii::t('app', 'Update password');
$this->params['breadcrumbs'][] = $this->title;
?>

<div id="statusMsg">
    <?php if (Yii::$app->session->hasFlash('success')): ?>
        <div class="alert alert-success">
            <?= \Yii::$app->getSession()->getFlash('success'); ?>
        </div>
    <?php endif; ?>

    <?php if (Yii::$app->session->hasFlash('error')): ?>
        <div class="alert alert-danger fade in">
            <?= yii\helpers\Json::decode(\Yii::$app->getSession()->getFlash('error'))['message']; ?>
        </div>
    <?php endif; ?>
</div>

<div class="update-password">
    <h1><?= Html::encode($this->title) ?></h1>

    <p><?= Yii::t('app', 'Please fill out the following fields to update your password:') ?></p>

    <?php $form = ActiveForm::begin([
        'id' => 'update-password',
        'options' => ['class' => 'form-horizontal'],
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-8 col-lg-offset-2\">{error}</div>",
            'labelOptions' => ['class' => 'col-lg-2 control-label'],
        ],
    ]); ?>

    <?= $form->field($model, 'email') ?>

    <?= $form->field($model, 'new_password')->passwordInput() ?>

    <?= $form->field($model, 'repeat_password')->passwordInput() ?>

    <?= $form->field($model, 'code') ?>

    <div class="form-group">
        <div class="col-lg-offset-2 col-lg-10">
            <?= Html::submitButton(Yii::t('app', 'Update password'),
                ['class' => 'btn btn-success', 'name' => 'update-password']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>