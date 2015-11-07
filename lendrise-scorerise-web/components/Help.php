<?php

namespace app\components;

use Yii;
use yii\helpers\Json;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

class Help
{
    const SCORE_MIN = 300;
    const SCORE_MAX = 850;
    const MIN_CREDIT_FOR_RECHECK = 1;

    /**
     * comment test
     */

    /**
     * @param $scor
     * @return float
     */
    public static function getProcentScor($scor)
    {
        return round((100 * $scor) / (self::SCORE_MAX), 2);
    }

    /**
     * @param $value
     * @param string $nullCase
     * @return string
     */
    public static function writeValue($value, $nullCase = 'N/A')
    {
        return is_null($value) ? $nullCase : $value;
    }

    /**
     * Get amount of available interrogation units
     * GET http://lr-eb-api-dev.elasticbeanstalk.com/api/interrogations
     * Produces: application/json
     * Headers: token
     * Response Body example:
     * {"data": {
     *      "userInterrogations": {
     *          "interrogationsAmout": 0,
     *          "userId": "a57da41f-3d10-4d04-bed9-2f42a11e79ad"
     *      }
     * }}
     *
     * @return string|void
     */
    public static function getCreditInfo()
    {
//        if (Yii::$app->user->isGuest) {
//            return;
//        }
        if (!isset(Yii::$app->session['token'])) {
            return;
        }

        $client = new Client();
        try {
            $res = $client->get(Yii::$app->params['apiUrl'] . 'interrogations', [
                'headers' => [
                    'content-type' => 'application/json',
                    'token' => Yii::$app->session['token']
                ]
            ]);

            $data = Json::decode($res->getBody()->getContents());

            return isset($data['data']['userInterrogations']['interrogationsAmout'])
                ? $data['data']['userInterrogations']['interrogationsAmout']
                : '';

        } catch (ClientException $e) {
            return;
        }
    }

    /**
     * @return bool
     */
    public static function isLogIn()
    {
        $session = Yii::$app->session;

        if (isset($session['token']) && isset($session['userId'])) {
            return true;
        }

        return false;
    }

    /**
     * @return string
     */
    public static function getNotifications()
    {
        $client = new Client();
        try {
            $res = $client->get(Yii::$app->params['apiUrl'] . 'notification', [
                'headers' => [
                    'content-type' => 'application/json',
                    'token' => Yii::$app->session['token']
                ]
            ]);

            $models = Json::decode($res->getBody()->getContents());
            $data = $models['data']['notifications']['read'] = 1;

            if (isset($models['data']['notifications'])) {
                $k = 0;
                foreach ($models['data']['notifications'] as $key) {
                    if ($key['read'] == 0) {
                        $k++;
                    }
                }

                return ($k > 0) ? ('<span class="notifyCircle">' . $k . '</span>') : '';
            }

            return '';
        } catch (ClientException $e) {
//            VarDumper::dump($e->getResponse()->getBody()->getContents());
//            Yii::$app->end();
            return '';
        }

    }
}