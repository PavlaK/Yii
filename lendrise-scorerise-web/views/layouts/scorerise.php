<?php
use app\components\Help;
use yii\bootstrap\ButtonDropdown;
use yii\bootstrap\Nav;
use yii\helpers\Html;
use yii\web\JqueryAsset;
use yii\widgets\Menu;
use app\assets\ScorRiseAsset;
use app\components\widgets\languageSelector\LanguageSelector;
use app\models\ProfileForm;

/* @var $this \yii\web\View */
/* @var $content string */

ScorRiseAsset::register($this);

// For Top Menu
$isLogin = Help::isLogIn();
$visible = $isLogin;
$hasProfile = null;
if ($visible) {
    $hasProfile = @ProfileForm::profileData()['message'];
}

$bUrl = Yii::$app->request->baseUrl;
$jQuery = [JqueryAsset::className()];

//$this->registerCssFile($bUrl . '/js/jquery-plugin-circliful-master/css/jquery.circliful.css',
//    ['position' => $this::POS_HEAD]);
$this->registerJsFile($bUrl . '/js/menu.js', ['depends' => $jQuery, 'position' => $this::POS_END]);

?>
<?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <title><?= Html::encode($this->title) ?></title>
        <meta name="description" content="<?= Html::encode($this->title) ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?= Html::csrfMetaTags() ?>
        <?php $this->head() ?>
    </head>
    <body>

    <?php $this->beginBody() ?>

    <div id="outer-wrap">
        <div id="inner-wrap">

            <header id="top" role="banner">
                <div class="block">
                    <a class="nav-btn" id="nav-open-btn" href="#nav"></a>

                    <nav id="nav" role="navigation">

                        <div id="logo">
                            <?= Html::img('@web/images/logo_scoreRise.png', ['class' => 'desktopLogo']); ?>
                        </div>

                        <?php
                        //                        if(isset($_SESSION['userId']))
                        //                            echo $_SESSION['userId'] . '<br/>';
                        //                        if(isset($_SESSION['token']))
                        //                            echo $_SESSION['token'] . '<br/>';
                        $credit = Help::getCreditInfo();
                        $credit = is_null($credit) ? '' : $credit;
                        ?>

                        <?= Menu::widget([
                            'options' => [
                                'class' => '',
                                'id' => 'navbar-top',
                            ],
                            'activeCssClass' => 'is-active',
                            'encodeLabels' => false,
                            'items' => [
                                [
                                    'label' => Yii::t('app', 'Home'),
                                    'url' => ['site/index']
                                ],
                                [
                                    'label' => Yii::t('app', 'My score'),
                                    'url' => ['/site/score'],
                                    'visible' => $visible && !$hasProfile
                                ],
                                [
                                    'label' => Yii::t('app', 'Notifications') . Help::getNotifications(),
                                    'url' => ['/site/notifications'],
                                    'visible' => $visible && !$hasProfile
                                ],
                                [
                                    'label' => Yii::t('app', 'History'),
                                    'url' => ['/site/history'],
                                    'visible' => $visible && !$hasProfile
                                ],
                                [
                                    'label' => Yii::t('app', 'Terms & Conditions'),
                                    'url' => ['/site/about'],
                                ],
                                [
                                    'label' => Yii::t('app', 'Contact'),
                                    'url' => ['/site/contact'],
                                ],
                                [
                                    'label' => Yii::t('app', 'Credit') . ' ' . Help::getCreditInfo(),
                                    'url' => ['#'],
                                    'visible' => $visible
                                ],
                            ],
                        ]);
                        ?>

                        <a class="close-btn" id="nav-close-btn" href="#top">Return to Content</a>

                        <div id="language">
                            <?= LanguageSelector::widget([
                                'displayAs' => 'link', // 'select'
                                'currentLang' => \Yii::$app->language,
                                'languages' => \Yii::$app->params['languages'],
                            ]);
                            ?>
                        </div>
                    </nav>

                    <div id="logo">
                        <?= Html::img('@web/images/smalllogo_scoreRise.png', ['class' => 'mobileLogo']); ?>
                    </div>

                    <?php if (!$visible) {
                        echo Html::button(Yii::t('app', 'Login'), [
                            'class' => 'blue',
                            'onclick' => 'window.location.href="' . Yii::$app->getUrlManager()->createUrl('site/login') . '";',
//                            'data-method' => 'post'
                        ]);
                    } else {
                        echo ButtonDropdown::widget([
                            'label' => Yii::t('app', 'Account'),
                            'options' => ['class' => 'blue'],
//                            'containerOptions' => ['style' => 'margin-top:16px !important;'],
                            'dropdown' => [
                                'items' => [
                                    [
                                        'label' => Yii::t('app', 'Profile'),
                                        'url' => ['/site/view-profile'],
                                        'visible' => $visible
                                    ],
                                    [
                                        'label' => Yii::t('app', 'Change password'),
                                        'url' => ['/site/change-password'],
                                        'visible' => $visible,
                                    ],
                                    [
                                        'label' => Yii::t('app', 'Logout'),
                                        'url' => ['/site/logout'],
                                        'linkOptions' => ['data-method' => 'post'],
                                    ]
                                ],
                            ],
                        ]);
//                        echo Html::button(Yii::t('app', 'Logout'), [
//                            'class' => 'blue',
//                            'onclick' => 'window.location.href="' . Yii::$app->getUrlManager()->createUrl('site/logout') . '";',
////                            'data-method' => 'post'
//                        ]);
                    }
                    ?>

                </div>
            </header>

            <div id="bannerImg">
                <?= Html::img('@web/images/scoreRise_banner.jpg', ['class' => 'mobileLogo']); ?>
            </div>

            <main role="main">
                <div class="wrap">
                    <section class="block">

                        <?php echo $content ?>

                    </section>
                </div>
            </main>

            <section id="downloadApp">
                <div class="wrap">
                    <h2>Download Application</h2>

                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut
                        labore et dolore magna aliqua.
                        Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
                        consequat.</p>
                    <a href="#">
                        <?= Html::img('@web/images/appStoreIOS.png', []); ?>
                    </a>
                </div>
            </section>

            <footer role="contentinfo">
                <div class="wrap block">
                    <?= Menu::widget([
                        'options' => [
                            'class' => '',
                            'id' => 'navbar-footer',
                        ],
                        'activeCssClass' => 'is-active',
                        'items' => [
                            [
                                'label' => Yii::t('app', 'Home'),
                                'url' => ['site/index']
                            ],
                            [
                                'label' => Yii::t('app', 'My score'),
                                'url' => ['/site/score'],
                                'visible' => $visible && !$hasProfile
                            ],
                            [
                                'label' => Yii::t('app', 'Notifications'),
                                'url' => ['/site/notifications'],
                                'visible' => $visible && !$hasProfile
                            ],
                            [
                                'label' => Yii::t('app', 'History'),
                                'url' => ['/site/history'],
                                'visible' => $visible && !$hasProfile
                            ],
                            [
                                'label' => Yii::t('app', 'Terms & Conditions'),
                                'url' => ['/site/about'],
                            ],
                            [
                                'label' => Yii::t('app', 'Contact'),
                                'url' => ['/site/contact'],
                            ]
                        ],
                    ]);
                    ?>

                    <span><?= Yii::$app->params['companyName'] ?> -
                        Copyright &copy;
                        <time datetime="<?= date('Y') ?>"><?= date('Y') ?></time>
                    </span>
                </div>
            </footer>

        </div>
    </div>

    <?php $this->endBody() ?>

    </body>
    </html>
<?php $this->endPage() ?>