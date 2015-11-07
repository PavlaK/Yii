<?php
/**
 * Created by PhpStorm.
 * User: Nicolae Alcea
 * Date: 2015-07-22
 * Time: 11:14 AM
 */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\jui\DatePicker;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\ProfileForm */

$this->title = Yii::t('app', 'Update profile');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="update-password">
    <h1><?= Html::encode($this->title) ?></h1>

    <p><?= Yii::t('app', 'Please fill out the following fields to update your profile:') ?></p>

    <?php $form = ActiveForm::begin([
        'id' => 'update-profile',
        'options' => [
            'class' => 'form-horizontal',
            'enctype' => 'multipart/form-data',
        ],
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-8 col-lg-offset-2\">{error}</div>",
            'labelOptions' => ['class' => 'col-lg-2 control-label'],
        ],
    ]); ?>

    <div class="form-group">
        <div class="col-lg-offset-2 col-lg-10">
            <?= Html::a('Re-Scan', ['upload-profile'], ['class' => 'btn btn-success']) ?></p>
        </div>
    </div>

<!--    --><?php //var_dump($errors); ?>

    <?= $form->field($model, 'firstName') ?>

    <?= $form->field($model, 'lastName') ?>

    <?= $form->field($model, 'cnp')->textInput(['readonly' => true]) ?>

    <?= $form->field($model, 'socialId')->textInput(['readonly' => true]) ?>

    <?= $form->field($model, 'dateOfBirth')->widget(DatePicker::className(),
        [
            'language' => Yii::$app->language,
            'dateFormat' => 'php:Y-m-d',
            'clientOptions' => [
                'language' => 'ro',
                'defaultDate' => isset($model->dateOfBirth) ? date('Y-m-d', strtotime($model->dateOfBirth)) : '',
                'showOn' => 'focus',
                // 'focus', 'button', 'both'
                'showButtonPanel' => true,
                // show button panel
                'duration' => 'fast',
                'showAnim' => 'slide',
                // efecte: 'slide','fold','slideDown','fadeIn','blind','bounce','clip','drop'
                'changeYear' => true,
                // can change year
                'changeMonth' => true,
                // can change month
                'yearRange' => '1930:2099',
                // range of year
                'minDate' => '1930-01-01',
                // minimum date, EX - cinci  zile in urma: -5,
                'maxDate' => '2099-12-31',
                // maximum date, EX - o luna si cinci zile inainte: "+1M +5D",
            ]
        ])->textInput(['style' => 'width:100px;']);
    ?>

    <?= $form->field($model, 'address') ?>

    <?= $form->field($model, 'image')->fileInput() ?>

    <div class="form-group">
        <div class="col-lg-offset-2 col-lg-10">
            <?= Html::submitButton(Yii::t('app', 'Update profile'),
                ['class' => 'btn btn-primary', 'name' => 'update-profile']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>