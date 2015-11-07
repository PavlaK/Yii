<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ArrayDataProvider */

$this->title = Yii::t('app', 'Notifications');
$this->params['breadcrumbs'][] = $this->title;
?>


<h1><?= Html::encode($this->title) ?></h1>

<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'rowOptions' => function ($model, $key, $index, $grid) {
        $rez = [
            'data-id' => $model['id'],
            'data-message' => $model['message'],
            'data-read' => $model['read'],
        ];

        return $rez;
    },
    'layout' => "\n{items}",
    'columns' => [
        [
            'class' => 'yii\grid\SerialColumn',
            'headerOptions' => ['style' => 'width:50px;'],
        ],
//            'id',
        [
            'attribute' => 'dateCreated',
            'label' => Yii::t('app', 'Date'),
            'format' => ['date', 'php:Y-m-d'],
            'headerOptions' => ['style' => 'width:12%;'],
        ],
        [
            'attribute' => 'message',
            'label' => Yii::t('app', 'Message'),
            'value' => function ($data) {
                return ($data['read'] == 0) ? '' : $data['message'];
            },
            'contentOptions' => function ($model, $key, $index, $column) {
                return ['class' => 'message_cell'];
            },
        ],
        [
            'attribute' => 'read',
            'label' => Yii::t('app', 'Read'),
            'format' => ['boolean'],
            'headerOptions' => ['style' => 'width:60px;'],
        ],
        [
            'class' => 'yii\grid\ActionColumn',
            'template' => '{view}',
            'headerOptions' => ['style' => 'width:40px;'],
            'buttons' => [
                'view' => function ($url, $data) {
                    if ($data['read'] == 1) {
                        return;
                    }

                    return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', '#',
                        [
                            'title' => Yii::t('app', 'View'),
                            'data-pjax' => '0',
                            'class' => 'view_new_msg',
                        ]);
                },
            ],
        ],
    ],
]) ?>


<script>
    $(document).ready(function () {
        //
        $(document).on('click', '.view_new_msg', function (event) {
            event.preventDefault();

            var row = $(this).closest("tr"),
                id = row.data("id"),
                message = row.data("message"),
                read = row.data("read");

            $.ajax({
                type: 'GET',
                cache: false,
                data: {
                    'notificationId': id
                },
                url: '<?= Url::to(['site/notification-read']) ?>',
                success: function (response) {
                    if (response == 'ok') {
                        row.find(".message_cell").html(message);
                        location.reload();
                    } else if (response == 'error') {
                        alert(response);
                    }
                }
            });

            return false;
        });
    });
</script>