<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\ProfileForm */

$this->title = Yii::t('app', 'Upload CI Photo');
$this->params['breadcrumbs'][] = $this->title;
?>

    <h1><?= Html::encode($this->title) ?></h1>

    <p><?= Yii::t('app', $this->context->renderPartial('terms')) ?></p>
<br/>

<?php if (Yii::$app->session->hasFlash('error')): ?>
    <div class="alert alert-danger">
        <?= Yii::$app->session->getFlash('error') ?>
    </div>
<?php endif; ?>

<?php $form = ActiveForm::begin([
    'id' => 'update-profile',
    'options' => [
        'class' => 'form-horizontal',
        'enctype' => 'multipart/form-data',
    ],
    'fieldConfig' => [
        'template' => "{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-8 col-lg-offset-1\">{error}</div>",
        'labelOptions' => ['class' => 'col-lg-1 control-label'],
    ],
]); ?>

    <div class="form-group">
        <div class="col-lg-offset-2 col-lg col-lg-10">
            <?= $form->field($model, 'image')->fileInput() ?>

            <?= $form->field($model, 'terms')->checkbox() ?>
        </div>
    </div>


    <div class="form-group">
        <div class="col-lg-offset-1 col-lg col-lg-10">
            <?= Html::a(Yii::t('app', 'Access camera from browser'), 'webcam', ['class' => 'btn btn-primary']) ?>
            <?= Html::submitButton('Scan photo', ['class' => 'btn btn-success', 'name' => 'update-profile']) ?>
        </div>
    </div>

<?php ActiveForm::end(); ?>