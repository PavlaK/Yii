<?php
/* @var $currentLang string */
/* @var $languages array */
/* @var $displayAs string */

use yii\helpers\Html;


if ($displayAs == 'select') {
    echo Html::beginForm($action = '', $method = 'post',
        $options = [
            'class' => 'navbar-form navbar-nav navbar-right'
        ]
    );

    echo '<div class="form-group">';
    echo Html::dropDownList('_lang', $currentLang, $languages,
        [
            'onchange' => 'changeLanguage(this)',
            'csrf' => true,
            'class' => 'form-group'
        ]
    );
    echo '</div>';
    echo Html::endForm();
} elseif ($displayAs == 'link') {
//    $lastElement = end($languages);
    foreach ($languages as $key => $lang) {
        if ($key != $currentLang) {
            echo Html::a($lang, '', ['data-lang' => $key, 'class' => '_lang']);
        } else {
            echo Html::a($lang, '', ['data-lang' => $key, 'class' => '_lang currentLang']);
        }
//        if ($lang != $lastElement) {
//            echo ' | ';
//        }
    }
}
?>
<script type="text/javascript">
    $(document).ready(function () {
        $(document).on('click', '._lang', function (event) {
            event.preventDefault();

            $.ajax({
                url: '<?= Yii::$app->getUrlManager()->createUrl("site/language") ?>',
                type: 'POST',
                data: {
                    _lang: $(this).data("lang"),
                    YII_CSRF_TOKEN: '<?= Yii::$app->request->csrfToken ?>'
                },
                success: function (data) {
                    window.location.reload();
                }
            });
        });
    });

    function changeLanguage(selected) {
        $.ajax({
            url: '<?= Yii::$app->getUrlManager()->createUrl("site/language") ?>',
            type: 'POST',
            data: {
                _lang: selected.value,
                YII_CSRF_TOKEN: '<?= Yii::$app->request->csrfToken ?>'
            },
            success: function (data) {
                window.location.reload();
            }
        });
    }
</script>