<?php
/**
 * Created by PhpStorm.
 * User: Nicolae Alcea
 * Date: 2015-08-05
 * Time: 5:36 PM
 */

use app\models\MobilPayBillingForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $billing app\models\MobilPayBillingForm */

$this->title = Yii::t('app', 'Pay');
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="site-pay">
    <h1><?= Html::encode($this->title) ?></h1>

    <?php if (Yii::$app->session->hasFlash('error')): ?>
        <div class="alert alert-danger">
            <?= Yii::$app->session->getFlash('error') ?>
        </div>
    <?php endif; ?>

    <?php $form = ActiveForm::begin([
        'id' => 'pay-form',
        'options' => [
            'class' => 'form-horizontal',
//            'style' => 'float:left; width: 50%'
        ],
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-lg-5\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>",
            'labelOptions' => ['class' => 'col-lg-2 control-label'],
        ],
    ]); ?>

    <p><?= Yii::t('app', 'Please fill out the following fields to pay:') ?></p>

    <?= $form->field($billing, 'type')->dropDownList([
        MobilPayBillingForm::PERSOANA_JURIDICA => Yii::t('app', 'Persoana juridica'),
        MobilPayBillingForm::PERSOANA_FIZICA => Yii::t('app', 'Persoana fizica')
    ], ['prompt' => '--Select--']) ?>

    <?php
    $items = ArrayHelper::map(Yii::$app->params['coins'], 'suma', 'text');
    echo $form->field($billing, 'coin')->dropDownList($items, [
        'style' => 'cursor: not-allowed'
    ]) ?>

    <?= $form->field($billing, 'firstName') ?>

    <?= $form->field($billing, 'lastName') ?>

    <?= $form->field($billing, 'fiscal_number') ?>

    <?= $form->field($billing, 'identity_number') ?>

    <?= $form->field($billing, 'country') ?>

    <?= $form->field($billing, 'county') ?>

    <?= $form->field($billing, 'city') ?>

    <?= $form->field($billing, 'zip_code') ?>

    <?= $form->field($billing, 'address')->textarea() ?>

    <?= $form->field($billing, 'email') ?>

    <?= $form->field($billing, 'mobilePhone') ?>

    <?= $form->field($billing, 'bank') ?>

    <?= $form->field($billing, 'iban') ?>

    <div class="form-group">
        <div class="col-lg-offset-2 col-lg-10">
            <?= Html::submitButton(Yii::t('app', 'Pay'), ['class' => 'btn btn-primary', 'name' => 'pay-button']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>