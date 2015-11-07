<?php

namespace app\models;

use Yii;
use yii\base\Model;

class AccountForm extends Model
{
    public $email;
    public $new_password;
    public $old_password;
    public $repeat_password;
    public $code;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['email'], 'required', 'on' => 'forgot'],
            [['email', 'new_password', 'repeat_password', 'code'], 'required', 'on' => 'update'],
            [['old_password', 'new_password', 'repeat_password'], 'required', 'on' => 'change'],
            [
                ['repeat_password'],
                'compare',
                'compareAttribute' => 'new_password',
                'on' => ['change', 'update'],
                'message' => Yii::t('app', 'Password must be repeated exactly!'),
            ],
            ['email', 'email'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'email' => Yii::t('app', 'Email'),
            'new_password' => Yii::t('app', 'New password'),
            'old_password' => Yii::t('app', 'Old password'),
            'repeat_password' => Yii::t('app', 'Repeat password'),
            'code' => Yii::t('app', 'Code'),
        ];
    }
}