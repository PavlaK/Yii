<?php

namespace app\components\widgets\languageSelector;

use yii\base\Widget;

class LanguageSelector extends Widget
{
    public $displayAs = 'link'; // select || link
    public $currentLang = 'en'; // default en
    public $languages = [];

    public function run()
    {
        return $this->render('languageSelector', [
            'displayAs' => $this->displayAs,
            'currentLang' => $this->currentLang,
            'languages' => $this->languages,
        ]);
    }
}