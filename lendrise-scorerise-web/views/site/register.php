<?php
use yii\authclient\widgets\AuthChoice;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

$this->title = Yii::t('app', 'Register');
$this->params['breadcrumbs'][] = $this->title;
?>

<h1><?= Html::encode($this->title) ?></h1>

<?php if (Yii::$app->session->hasFlash('error')): ?>
    <div class="alert alert-danger">
        <?= Yii::$app->session->getFlash('error') ?>
    </div>
<?php endif; ?>

<?php $form = ActiveForm::begin([
    'id' => 'login-form',
    'options' => ['class' => 'form-horizontal', 'style' => 'float:left; width: 50%'],
    'fieldConfig' => [
        'template' => "{label}\n<div class=\"col-lg-5\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>",
        'labelOptions' => ['class' => 'col-lg-2 control-label'],
    ],
]); ?>

<p><?= Yii::t('app', 'Please fill out the following fields to register:') ?></p>
<div class="form-group">
    <div class="col-lg-offset-1 col-lg-11">
        <?= $form->field($register, 'username') ?>

        <?= $form->field($register, 'password')->passwordInput() ?>

        <?= $form->field($register, 'verifyCode')->widget(Captcha::className(), [
            'template' => '<div class="row"><div class="col-lg-6">{image}</div><div class="col-lg-6">{input}</div></div>',
        ]) ?>
    </div>
</div>

<div class="form-group">
    <div class="col-lg-offset-1 col-lg-11">
        <?= Html::submitButton(Yii::t('app', 'Create account'),
            ['class' => 'btn btn-success', 'name' => 'login-button']) ?>
    </div>
</div>

<?php ActiveForm::end(); ?>

<div class="form-horizontal" style="float:left;padding-left:30%">
    <?= AuthChoice::widget([
        'baseAuthUrl' => ['site/auth'],
    ]); ?>
</div>
