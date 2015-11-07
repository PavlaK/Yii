<?php

use yii\helpers\Html;

/* @var $this yii\web\View */

$this->title = Yii::t('app', 'Profile');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="change-password">
    <h1><?= Html::encode($this->title) ?></h1>

    <div class="panel panel-default">
        <!-- Default panel contents -->
        <div class="panel-heading"><?= Yii::t('app', 'USER PROFILE') ?></div>

        <?php if (isset($model['message'])) : ?>
            <div class="panel-body">
                <p><?= $model['message']; ?></p>
            </div>
        <?php elseif (isset($model['data']['userProfile'])) : ?>
            <table class="table">
                <?php if (isset($model['data']['userProfile']['socialId'])): ?>
                    <tr>
                        <td><?= Yii::t('app', 'FIRST NAME') ?></td>
                        <td><?= $model['data']['userProfile']['firstName'] ?></td>
                    </tr>
                <?php endif; ?>

                <?php if (isset($model['data']['userProfile']['socialId'])): ?>
                    <tr>
                        <td><?= Yii::t('app', 'LAST NAME') ?></td>
                        <td><?= $model['data']['userProfile']['lastName'] ?></td>
                    </tr>
                <?php endif; ?>

                <?php if (isset($model['data']['userProfile']['cnp'])): ?>
                    <tr>
                        <td style="width: 20%"><?= Yii::t('app', 'CNP') ?></td>
                        <td><?= $model['data']['userProfile']['cnp'] ?></td>
                    </tr>
                <?php endif; ?>

                <?php if (isset($model['data']['userProfile']['socialId'])): ?>
                    <tr>
                        <td><?= Yii::t('app', 'SOCIALID') ?></td>
                        <td><?= $model['data']['userProfile']['socialId'] ?></td>
                    </tr>
                <?php endif; ?>

                <?php if (isset($model['data']['userProfile']['address'])): ?>
                    <tr>
                        <td><?= Yii::t('app', 'ADDRESS') ?></td>
                        <td><?= $model['data']['userProfile']['address'] ?></td>
                    </tr>
                <?php endif; ?>

                <?php if (isset($model['data']['userProfile']['dateOfBirth'])): ?>
                    <tr>
                        <td><?= Yii::t('app', 'Date of birth') ?></td>
                        <td><?= Yii::$app->formatter->asDate($model['data']['userProfile']['dateOfBirth']) ?></td>
                    </tr>
                <?php endif; ?>

            </table>
        <?php endif; ?>
    </div>

    <p><?= Html::a('Update profile', ['update-profile'], ['class' => 'btn btn-success']) ?>

</div>