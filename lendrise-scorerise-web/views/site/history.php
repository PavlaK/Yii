<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\web\JqueryAsset;
use app\components\Help;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $dataProvider yii\data\ArrayDataProvider */

$this->title = Yii::t('app', 'History score');
$this->params['breadcrumbs'][] = $this->title;

$bUrl = Yii::$app->request->baseUrl;
$jQuery = [JqueryAsset::className()];

$this->registerJsFile($bUrl . '/js/raphael/raphael-min.js', ['depends' => $jQuery]);
$this->registerJsFile($bUrl . '/js/morris/morris.min.js', ['depends' => $jQuery]);
$this->registerJs("
    // Use Morris.Bar
    Morris.Line({
      element: 'morris_graph',
      data: " . $morisData . ",
//          [
//            {date: '2011-07-21', scor: 560},
//            {date: '2012-01-31', scor: 735},
//          ],
      hideHover: true,
      resize: true,
      xkey: 'date',
      ykeys: ['scor'],
      labels: ['Scor'],
      ymin: " . Help::SCORE_MIN . ",
      ymax: " . Help::SCORE_MAX . ",
    }).on('click', function(i, row){
      console.log(i, row);
    });
");
?>

<div class="update-history">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="container-fluid" style="height: 280px;">
        <div id="morris_graph" style="height: 200px; margin: 20px auto 0;"></div>
    </div>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'layout' => "\n{items}",
        'columns' => [
            [
                'class' => 'yii\grid\SerialColumn',
                'headerOptions' => ['style' => 'width:50px;'],
            ],
            [
                'attribute' => 'date',
                'label' => Yii::t('app', 'Date'),
                'format' => ['date', 'php:Y-m-d']
            ],
            [
                'attribute' => 'score',
                'label' => Yii::t('app', 'Score'),
            ],
//            'recordId',
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view}',
                'headerOptions' => ['style' => 'width:40px;'],
                'buttons' => [
                    'view' => function ($url, $data) {
                        $url = Yii::$app->getUrlManager()->createUrl([
                            'site/score-for-date',
                            'recordId' => $data['recordId']
                        ]);

                        return Html::a('<span class="glyphicon glyphicon-menu-hamburger"></span>', $url,
                            ['title' => Yii::t('app', 'History detail'), 'data-pjax' => '0']);
                    },
                ],
            ],
        ],
    ]) ?>

</div>