<?php
/**
 * Created by PhpStorm.
 * User: Nicolae Alcea
 * Date: 2015-07-22
 * Time: 10:58 AM
 */

namespace app\models;

use Yii;
use yii\base\Model;

class UploadForm extends Model
{
    public $image;
    public $terms;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['image'], 'required'],
            [
                'terms',
                'required',
                'requiredValue' => 1,
                'message' => Yii::t('app', 'You must agree with the terms and conditions.')
            ],
            [['image'], 'file', 'extensions' => 'png, jpg, gif, jpeg, bmp, pdf'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'image' => Yii::t('app', 'Image'),
            'terms' => Yii::t('app', 'I agree with the terms and conditions'),
        ];
    }
}