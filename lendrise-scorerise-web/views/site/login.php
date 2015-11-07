<?php
use yii\authclient\widgets\AuthChoice;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

$this->title = Yii::t('app', 'Login');
$this->params['breadcrumbs'][] = $this->title;
?>

<h1><?= Html::encode($this->title) ?></h1>

<p><?= Yii::t('app', 'Please fill out the following fields to login:') ?></p>

<?php if (Yii::$app->session->hasFlash('error')): ?>
    <div class="alert alert-danger">
        <?= Yii::$app->session->getFlash('error') ?>
    </div>
<?php endif; ?>

<?php $form = ActiveForm::begin([
    'id' => 'login-form',
    'options' => ['class' => 'dataInputs'],
    'fieldConfig' => [
//        'template' => "{label}\n<div class=\"col-lg-5\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>",
//        'labelOptions' => ['class' => 'col-lg-2 control-label'],
    ],
]); ?>


<div class="dataInputs">
    <div class="inputRow">
        <?= $form->field($model, 'username') ?>

        <?= $form->field($model, 'password')->passwordInput() ?>
    </div>

    <div class="inputRow">
        <?= Html::submitButton(Yii::t('app', 'Login'), ['class' => 'btn btn-success', 'name' => 'login-button']) ?>
        <?= Html::a(Yii::t('app', 'Forgot password?'), 'forgot-password', ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Create account'), 'register', ['class' => 'btn btn-info']) ?>
    </div>
</div>

<?php ActiveForm::end(); ?>


<div class="dataInputs">
    <div class="inputRow"><span>First Name<b>*</b></span> <input type="text"></div>
    <div class="inputRow"><span>Last Name<b>*</b></span><input type="text"></div>
    <div class="inputRow"><span>Social ID<b>*</b></span> <input type="text"></div>
    <div class="inputRow"><span>CNP<b>*</b></span> <input type="text"></div>
    <div class="inputRow"><span>Date of Birth</span> <input type="date"></div>
    <div class="inputRow"><span>Adress</span> <input type="text"></div>
</div>


</div>
<?= AuthChoice::widget([
    'baseAuthUrl' => ['site/auth']
]); ?>
</div>

    <!--    <a href="#">-->
    <!--        <img src="images/facebook.png">-->
    <!--    </a>-->
    <!--    <a href="#">-->
    <!--        <img src="images/google.png">-->
    <!--    </a>-->
    <!--    <a href="#">-->
    <!--        <img src="images/linkedin.png">-->
    <!--    </a>-->