<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\helpers\Json;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

class ProfileForm extends Model
{
    public $address;
    public $cnp;
    public $dateOfBirth;
    public $firstName;
    public $lastName;
    public $socialId;
    public $image;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['firstName', 'lastName', 'cnp', 'socialId'], 'required'],
            [['cnp'], 'string', 'length' => 13],
            [['firstName', 'lastName', 'address', 'dateOfBirth'], 'string'],
            [['image'], 'file', 'extensions' => 'png, jpg, gif, jpeg'],
            [['dateOfBirth', 'address'], 'safe'],
        ];
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'address' => Yii::t('app', 'Address'),
            'cnp' => Yii::t('app', 'CNP'),
            'dateOfBirth' => Yii::t('app', 'Date of birth'),
            'firstName' => Yii::t('app', 'First name'),
            'lastName' => Yii::t('app', 'Last name'),
            'socialId' => Yii::t('app', 'Social Id'),
            'image' => Yii::t('app', 'Image'),
        ];
    }

    /**
     * @return mixed
     */
    public static function profileData()
    {
        $client = new Client();
        try {
            $res = $client->get(Yii::$app->params['apiUrl'] . 'profile', [
                'headers' => [
                    'content-type' => 'application/json',
                    'token' => \Yii::$app->session['token']
                ]
            ]);
            $data = Json::decode($res->getBody());
        } catch (ClientException $e) {
            $data = Json::decode($e->getResponse()->getBody()->getContents());
        }

        return $data;
    }
}