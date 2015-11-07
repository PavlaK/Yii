<?php

namespace app\models;

use Yii;
use yii\base\Model;
use \yii\helpers\Json;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

/**
 * LoginForm is the model behind the login form.
 */
class LoginForm extends Model
{
    public $username;
    public $password;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['username', 'password'], 'required'],
        ];
    }

    /**
     * Logs in a user using the provided username and password.
     * @return boolean whether the user is logged in successfully
     */
    public function login()
    {
        $client = new Client();
        $post = \Yii::$app->request->post('LoginForm');
        try {
            $res = $client->post(Yii::$app->params['apiUrl'] . 'signin', [
                'body' => Json::encode([
                    'email' => $post['username'],
                    'password' => $post['password']
                ]),
                'headers' => ['content-type' => 'application/json']
            ]);
            $credentials = Json::decode($res->getBody()->getContents())['data']['userAccount'];
        } catch (ClientException $e) {
            $error = Json::decode($e->getResponse()->getBody()->getContents())['message'];
            Yii::$app->session->setFlash('error', $error);
            return false;
        }

        $this->setLoginSession($credentials);

        return true;
    }

    /**
     * @param $credentials
     */
    private function setLoginSession($credentials)
    {
        $session = Yii::$app->session;

        $session->set('userId', $credentials['userId']);
        $session->set('token', $credentials['token']);
    }
}
