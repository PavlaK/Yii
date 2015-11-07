<?php
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;
use app\components\Help;
use app\components\widgets\languageSelector\LanguageSelector;
use app\models\ProfileForm;

/* @var $this \yii\web\View */
/* @var $content string */

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
    </head>
    <body>

    <?php $this->beginBody() ?>
    <div class="wrap">
        <?php
        NavBar::begin([
            'brandLabel' => Yii::$app->params['companyName'],
            'brandUrl' => Yii::$app->homeUrl,
            'options' => [
                'class' => 'navbar-inverse navbar-fixed-top',
            ],
        ]);

        $visible = Yii::$app->user->isGuest;
        $hasProfile = null;
        if (!$visible) {
            $hasProfile = !@ProfileForm::profileData()['message'];
        }

        $items = [
//            [
//                'label' => '<span class="glyphicon glyphicon-home"></span> ' . Yii::t('app', 'Home'),
//                'url' => ['/site/index'],
//                'visible' => !$visible
//            ],
            [
                'label' => '<span class="glyphicon glyphicon-dashboard"></span> ' . Yii::t('app', 'My score'),
                'url' => ['/site/score'],
                'visible' => !$visible && $hasProfile
            ],
            [
                'label' => '<span class="glyphicon glyphicon-bell"></span> ' . Yii::t('app', 'Notifications'),
                'url' => ['/site/notifications'],
                'visible' => !$visible && $hasProfile
            ],
            [
                'label' => '<span class="glyphicon glyphicon-signal"></span> ' . Yii::t('app', 'History'),
                'url' => ['/site/history'],
                'visible' => !$visible && $hasProfile
            ],
            [
                'label' => '<span class="glyphicon glyphicon-folder-open"></span> ' . Yii::t('app', 'Terms & Conditions'),
                'url' => ['/site/about'],
            ],
            [
                'label' => '<span class="glyphicon glyphicon-comment"></span> ' . Yii::t('app', 'Contact'),
                'url' => ['/site/contact'],
            ],
            [
                'label' => '<span class="glyphicon glyphicon-log-in"></span> ' . Yii::t('app', 'Login'),
                'url' => ['/site/login'],
                'visible' => $visible
            ],
            [
                'label' => '<span class="glyphicon glyphicon-user"></span> ' . Yii::t('app', 'Account'),
                'items' => [
                    [
                        'label' => '<span class="glyphicon glyphicon-list-alt"></span> ' . Yii::t('app', 'Profile'),
                        'url' => ['/site/view-profile']
                    ],
                    [
                        'label' => '<span class="glyphicon glyphicon-refresh"></span> ' . Yii::t('app', 'Change password'),
                        'url' => ['/site/change-password']
                    ],
                    [
                        'label' => '<span class="glyphicon glyphicon-off"></span> ' . Yii::t('app', 'Logout'),
                        'url' => ['/site/logout'],
                        'linkOptions' => ['data-method' => 'post'],
                    ]
                ],
                'visible' => !$visible
            ],
            [
                'label' => Help::getCreditInfo(Yii::t('app', 'Credit')),
                'visible' => !$visible
            ]
        ];

        echo Nav::widget([
            'options' => ['class' => 'navbar-nav navbar-right'],
            'encodeLabels' => false,
            'items' => $items,
        ]);

        echo LanguageSelector::widget([
            'displayAs' => 'link', // 'select'
            'currentLang' => \Yii::$app->language,
            'languages' => \Yii::$app->params['languages'],
        ]);

        NavBar::end();
        ?>

        <div class="container">
            <?= Breadcrumbs::widget([
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]) ?>
            <?= $content ?>
        </div>
    </div>

    <footer class="footer">
        <div class="container">
            <p class="pull-left">&copy;<?= Yii::$app->params['companyName'] . ' ' . date('Y') ?></p>

            <p class="pull-right"><?= Yii::powered() ?></p>
        </div>
    </footer>

    <?php $this->endBody() ?>
    </body>
    </html>
<?php $this->endPage() ?>