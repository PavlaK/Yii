<?php
use app\components\Help;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JqueryAsset;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model array */


/**
 *  if   condition
 *      replace entire graph (image, score...) with a new text in an html placeholder
 *          text comes from translation file app.php using yii::t
 *
 *      hide the left button from the row below that has two buttons
 *      raise the entire row with the two buttons just beneath the new placeholder
 */
$this->title = Yii::t('app', 'Credit Score');
$this->params['breadcrumbs'][] = $this->title;

$bUrl = Yii::$app->request->baseUrl;
$jQuery = [JqueryAsset::className()];

//$this->registerCssFile($bUrl . '/js/jquery-plugin-circliful-master/css/jquery.circliful.css',
//    ['position' => $this::POS_HEAD]);
$this->registerJsFile($bUrl . '/js/Chart.min.js', ['depends' => $jQuery]);

$this->registerJs("
    // send score to email
    $(document).on('click', '#score-report', function (event) {
        event.preventDefault();
        var msg = '" . Yii::t('app', 'The report was sent by email!') . "';
        var err_msg = '" . Yii::t('app', 'Error, please try later!') . "';

        $.ajax({
            type: 'POST',
            cache: false,
            dataType: 'html',
            url: '" . Url::to(['site/score-report']) . "',
            success: function (response) {
                if (response == 'ok') {
                    alert(msg);
                } else if (response == 'error') {
                    alert(err_msg);
                }
            }
        });

        return false;
    });
");

$infoImg = Html::img('@web/images/infoButton.png');

?>


<!--TO DO-->
<!---->
<!-- if  the number is <= 0-->
<!--      replace entire graph (image, score...) with a new text in an html placeholder-->
<!--        text comes from translation file app.php using yii::t-->
<!---->
<!--      hide the left button from the row below that has two buttons-->
<!--     raise the entire row with the two buttons just beneath the new placeholder-->

<?php if(intval($model['data']['briefScoreData']['scoreInfo']['creditScore']) >= 0) : ?>
<div id="canvas-holder">
    <canvas id="chart-area" width="500" height="500"/>
</div>

<div id="chartScore">
    <h1><?= Help::writeValue($model['data']['briefScoreData']['scoreInfo']['creditScore']) ?></h1>
    <span>good</span>
</div>

<div id="chartLegend">
    <span class="left"><?= Help::SCORE_MIN; ?></span>
    <?= Yii::t('app', 'Last scaned at') ?>
    <?= Yii::$app->formatter->asDatetime($model['data']['briefScoreData']['lastUpdated'], 'php:Y-m-d') ?>
    <span class="right"><?= Help::SCORE_MAX; ?></span>
</div>


<div id="scoreMessage">
    <div class="messageCircle">3.2%</div>
    <p> <?= Yii::t('app', 'Your credit ranks higher than 3.2% of Romanian population'); ?></p>
    <?= Html::a($infoImg,
        Url::to(Yii::$app->params['lendRiseSite']),
        ['title' => Yii::t('app', 'Your credit score')]
    ) ?>
</div>

<div id="creditNumbers">
    <div class="row">
        <span><?= Help::writeValue($model['data']['briefScoreData']['scoreInfo']['creditScore']) ?></span>

        <p><?= Yii::t('app', 'Your credit score') ?></p>

<!--        --><?//= Html::a($infoImg,
//            Url::to(Yii::$app->params['lendRiseSite']),
//            ['title' => Yii::t('app', 'Your credit score')]
//        ) ?>
    </div>

    <div class="row">
        <span><?= Yii::$app->formatter->asCurrency($model['data']['briefScoreData']['totalBalance']) ?></span>

        <p><?= Yii::t('app', 'Total balance'); ?></p>
    </div>

    <div class="row">
        <span><?= Yii::$app->formatter->asCurrency($model['data']['briefScoreData']['monthlyPayment']) ?></span>

        <p><?= Yii::t('app', 'Monthly payment'); ?></p>
    </div>

    <div class="row">
        <span><?= Help::writeValue($model['data']['briefScoreData']['totalAccountNumber']) ?></span>

        <p><?= Yii::t('app', 'Total number of account'); ?></p>
    </div>

    <div class="creditStatistics">

        <?php if (isset($model['data']['briefScoreData'])): ?>

        <div class="leftSide">
            <strong><?= Help::writeValue($model['data']['briefScoreData']['openAccounts']) ?> </strong>
            <?= Yii::t('app', 'Open'); ?>
        </div>
        <div class="rightSide">
            <strong><?= Help::writeValue($model['data']['briefScoreData']['closedAccounts']) ?></strong>
            <?= Yii::t('app', 'Closed'); ?>
        </div>
        <div class="leftSide">
            <strong><?= Help::writeValue($model['data']['briefScoreData']['delinguentAccounts']) ?></strong>
            <?= Yii::t('app', 'Deliguent'); ?>
        </div>
        <div class="rightSide">
            <strong><?= Help::writeValue($model['data']['briefScoreData']['derogatoryAccounts']) ?></strong>
            <?= Yii::t('app', 'Derogatory'); ?>
        </div>

        <?php endif; ?>

    </div>
</div>

    <!--    if the number is <= show this placeholder instead -->
<? else: ?>
<div id="message-Holder"><?= Yii::t('app', 'No credit check has been made'); ?></div>
    <style type="text/css">#recheckBtn {display:none;}</style>

<?php endif; ?>

<div id="adsArea">Ads Placeholder</div>

<div id="buttonsArea">
    <!--    <button >Re-Check Score</button>-->

    <?= Html::button(Yii::t('app', 'Re-check score'), [
        'onclick' => "window.location.href = " . "'" . Yii::$app->getUrlManager()->createUrl('site/cbscore') . "';",
        'class' => 'green',
        'id' => 'recheckBtn',
    ]) ?>

    <div class="coinsArea">
        <?php
        $items = ArrayHelper::map(Yii::$app->params['coins'], 'suma', 'text');
        echo Html::dropDownList('coins', null,
            $items,
            $options = ['class' => 'form-control"'])
        ?>

        <?= Html::button(Yii::t('app', 'Buy coins'), ['id' => 'bay_coins', 'class' => 'green']) ?>

        <script>
            $(document).ready(function () {
                $('#bay_coins').click(function (event) {
                    event.preventDefault();

                    var form = $('.coinsArea').find('select').serialize(),
                        url = '<?= Yii::$app->getUrlManager()->createUrl('site/mobil-pay') ?>';

                    window.location = url + '?' + form;

                    return false;
                });
            });
        </script>
    </div>
</div>

<div id="newsletterArea">
    <?php
    $emailLink = Html::img('@web/images/iconEmail.png', []) .
        '<span>' . Yii::t('app', 'Receive report via email') . '</span>' .
        Html::img('@web/images/iconEmailArrow.png', []);

    echo Html::a($emailLink, ['#'], ['id' => 'score-report']);
    ?>
</div>

<!--<script src="js/menu.js"></script>-->

<script>
    var min_score = '<?= Help::SCORE_MIN; ?>',
        max_score = '<?= Help::SCORE_MAX; ?>',
        score = '<?= Help::writeValue($model['data']['briefScoreData']['scoreInfo']['creditScore']) ?>';

    score = (score == 'N/A') ? 0 : score;

    var doughnutData = [
        {
            value: score,
            color: "#fcb275",
            highlight: "#fcb275",
            label: "<?= Yii::t('app', 'Your credit score') ?>"
        },
        {
            value: max_score - score,
            color: "#eee",
            highlight: "#eee",
            label: ""
        },
        {
            value: max_score,
            color: "#fff",
            highlight: "#fff",
            label: ""
        }
    ];

    window.onload = function () {
        var ctx = document.getElementById("chart-area").getContext("2d");
        window.myDoughnut = new Chart(ctx).Doughnut(doughnutData, {
            responsive: true,
            segmentShowStroke: false,
            percentageInnerCutout: 80,
            animationEasing: "easeOut",
            scaleShowLabels: false
        });
    };
</script>
