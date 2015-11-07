<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Json;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\AccountForm */

$this->title = Yii::t('app', 'Forgot password');
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
            <?= Json::decode(\Yii::$app->getSession()->getFlash('error'))['message']; ?>
        </div>
    <?php endif; ?>
</div>

<div class="forgot-password">
    <h1><?= Html::encode($this->title) ?></h1>

    <p><?= Yii::t('app', 'Please fill out the following field to reset your password:'); ?></p>

    <?php $form = ActiveForm::begin([
        'id' => 'forgot-password',
        'options' => ['class' => 'form-horizontal'],
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>",
            'labelOptions' => ['class' => 'col-lg-1 control-label'],
        ],
    ]); ?>

    <?= $form->field($model, 'email') ?>

    <div class="form-group">
        <div class="col-lg-3">
            <?= Html::a(Yii::t('app', 'Enter code'), 'update-password') ?>
        </div>
    </div>

    <div class="form-group">
        <div class="col-lg-offset-1 col-lg-11">
            <?= Html::submitButton(Yii::t('app', 'Forgot password'),
                ['class' => 'btn btn-success', 'name' => 'forgot-password']) ?>
            <?= Html::a(Yii::t('app', 'Update password'), 'update-password', ['class' => 'btn btn-primary']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>