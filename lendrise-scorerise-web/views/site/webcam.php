<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\UploadForm */

$this->title = Yii::t('app', 'Webcam');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="site-webcam">
    <h1><?= Html::encode($this->title) ?></h1>

    <div class="container-fluid">
        <div class="row">
            <!-- WEB cam -->
            <div class="col-md-4">
                <div id="web_cam"></div>
                <br/>
            </div>

            <!-- Form -->
            <div class="col-md-4">
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
                    <div class="col-lg col-lg-12">
                        <?= $form->field($model, 'terms')->checkbox() ?>

                        <?= $form->field($model, 'image')->hiddenInput(['id' => 'hiddenImage'])->label(false) ?>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-lg col-lg-12">

                        <!-- A button for taking snaps -->
                        <?= Html::button(Yii::t('app', 'Take Snapshot'), [
                            'id' => 'takeSnapshot',
                            'class' => 'btn btn-primary',
                            'onClick' => 'take_snapshot();'
                        ]) ?>

                        <?= Html::submitButton(Yii::t('app', 'Upload photo'), [
                            'class' => 'btn btn-success',
                            'name' => 'update-profile'
                        ]) ?>
                    </div>
                </div>

                <?php ActiveForm::end(); ?>

            </div>

            <!-- Snapshot -->
            <div class="col-md-4">
                <div id="results" style="float:right; margin:20px; padding:20px; border:1px solid; background:#ccc;">
                    <?= Yii::t('app', 'Your captured image will appear here...') ?>
                </div>
            </div>
        </div>
    </div>

    <!-- First, include the Webcam.js JavaScript Library -->
    <script type="text/javascript" src="../js/webcam/webcam.js"></script>

    <script language="JavaScript">
        // Configure a few settings and attach camera
        Webcam.set({
            width: 320,
            height: 240,
            image_format: 'jpeg',
            jpeg_quality: 90
        });

        Webcam.attach('#web_cam');

        // Code to handle taking the snapshot and displaying it locally
        function take_snapshot() {
            // take snapshot and get image data
            Webcam.snap(function (data_uri) {
                // display results in page
                document.getElementById('results').innerHTML =
                    '<h2><?= Yii::t('app', 'Your snapshot') ?></h2>' +
                    '<img src="' + data_uri + '"/>';

                // add image to hidden field
                var raw_image_data = data_uri.replace(/^data\:image\/\w+\;base64\,/, '');

                document.getElementById('hiddenImage').value = raw_image_data;
                // submit
                document.getElementById('update-profile').submit();
            });

        }
    </script>
</div>