<?php

namespace app\models;

use Yii;
use yii\base\Model;
use \yii\helpers\Json;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Client;

/**
 * RegisterForm is the model behind the login form.
 */
class RegisterForm extends Model
{
    public $username;
    public $password;
    public $verifyCode;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['username', 'password'], 'required'],
            // verifyCode needs to be entered correctly
            ['verifyCode', 'captcha'],
        ];
    }

    /**
     * Logs in a user using the provided username and password.
     * @return boolean whether the user is logged in successfully
     */
    public function register()
    {
        $client = new Client();
        $post = \Yii::$app->request->post('RegisterForm');
        try {
            $res = $client->post(Yii::$app->params['apiUrl'] . 'signup', [
                'body' => Json::encode(['email' => $post['username'], 'password' => $post['password']]),
                'headers' => ['content-type' => 'application/json']
            ]);
            $credentials = Json::decode($res->getBody()->getContents())['data']['userAccount'];
        } catch (ClientException $e) {
            $error = Json::decode($e->getResponse()->getBody()->getContents())['message'];
            Yii::$app->session->setFlash('error', $error);
            return false;
        }

        return Yii::$app->user->loginByAccessToken($credentials['token']);
    }
}
