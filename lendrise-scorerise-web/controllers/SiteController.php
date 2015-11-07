<?php

namespace app\controllers;

use Yii;
use yii\data\ArrayDataProvider;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\helpers\VarDumper;
use yii\web\Controller;
use yii\web\Cookie;
use yii\web\UploadedFile;
use app\components\mobilPay\Payment\Mobilpay_Payment_Address;
use app\components\mobilPay\Payment\Mobilpay_Payment_Invoice;
use app\components\mobilPay\Payment\Request\Mobilpay_Payment_Request_Card;
use app\components\mobilPay\Payment\Request\Mobilpay_Payment_Request_Abstract;
use app\components\Help;
use app\models\AccountForm;
use app\models\ContactForm;
use app\models\LoginForm;
use app\models\ProfileForm;
use app\models\RegisterForm;
use app\models\MobilPayBillingForm;
use app\models\UploadForm;
use Exception;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Client;

class SiteController extends Controller
{
    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => [
                            'login',
                            'error',
                            'forgot-password',
                            'update-password',
                            'auth',
                            'language',
                            'captcha',
                            'about',
                            'contact',
                            'register',
                            'logout',
                            'index',
                            'history',
                            'score',
                            'mobil-pay',
                            'mobil-pay-confirm',
                            'mobil-pay-return',
                            'cbscore',
                            'score-for-date',
                            'score-report',
                            'captcha',
                            'change-password',
                            'view-profile',
                            'update-profile',
                            'upload-profile',
                            'notifications',
                            'notification-read',
                            'webcam'
                        ],
                        'allow' => true,
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
//                    'logout' => ['post'],
                    'language' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @return array
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
            'auth' => [
                'class' => 'yii\authclient\AuthAction',
                'successCallback' => [$this, 'successCallback'],
            ],
        ];
    }

    /**
     * Social Sign In
     * POST http://lr-eb-api-dev.elasticbeanstalk.com/api/social/signin/{provider}
     * NOTE: {provider} could be linkedin, google, facebook
     * Consumes: application/json
     * Produces: application/json
     * Body parameters: socialId, socialToken
     * Headers: device_brand, device_model, device_id
     * Response Body example:
     * {  "data": {
     *      "userAccount": {
     *          "token": "a15b013a-cff6-4df2-a010-d0c0210ae778",
     *          "userId": "a57da41f-3d10-4d04-bed9-2f42a11e79ad"
     *      }
     * }
     *
     * @param $client
     * @return bool
     */
    public function successCallback($client)
    {
        if (isset($_GET['authclient'])) {
            if ($_GET['authclient'] == 'linkedin') {
                $objName = 'LinkedIn';
                $provider = $_GET['authclient'];
            } elseif ($_GET['authclient'] == 'google') {
                $objName = 'GoogleOAuth';
                $provider = 'googleplus';
            } elseif ($_GET['authclient'] == 'facebook') {
                $objName = 'Facebook';
                $provider = $_GET['authclient'];
            }
        }

        $nestedObject = [];

        foreach ($_SESSION as $key => $value) {
            if (strpos($key, $objName) !== false) {
                $nestedObject[] = $value;
            }
        }

        // get user data from client
        $userAttributes = $client->getUserAttributes();

        $socialId = $userAttributes['id'];
        $socialToken = $nestedObject[0]->getParams()['access_token'];

//        echo '<pre>';
//        print_r($_GET);
//        print_r($_SESSION);
//        print_r($userAttributes);
//        exit();

        // user login or signup comes here
        $guzzle = new Client();

        try {
            $res = $guzzle->post(Yii::$app->params['apiUrl'] . "social/signin/$provider", [
                'body' => Json::encode([
                    'socialId' => $socialId,
                    'socialToken' => $socialToken
                ]),
                'headers' => [
                    'content-type' => 'application/json'
                ]
            ]);

            $credentials = Json::decode($res->getBody()->getContents())['data']['userAccount'];

            if (isset($credentials['userId']) && isset($credentials['token'])) {
                // set session
                $session = Yii::$app->session;
                $session->set('userId', $credentials['userId']);
                $session->set('token', $credentials['token']);

                return true;
            }

            return false;

        } catch (ClientException $e) {
            echo $e->getResponse()->getBody()->getContents();
            exit('error');
        }
    }

    /**
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * @return string|\yii\web\Response
     */
    public function actionLogin()
    {
        if (Help::isLogIn()) {
            return $this->goHome();
        }

        $model = new LoginForm();

        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    /**
     * @return string|\yii\web\Response
     */
    public function actionRegister()
    {
        if (Help::isLogIn()) {
            return $this->goHome();
        }

        $register = new RegisterForm();

        if ($register->load(Yii::$app->request->post()) && $register->validate() && $register->register()) {
            return $this->goBack();
        } else {
            return $this->render('register', [
                'register' => $register
            ]);
        }
    }

    /**
     * @return \yii\web\Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        unset($_SESSION['token']);
        unset($_SESSION['userId']);

        return $this->goHome();
    }

    /**
     * @return string|\yii\web\Response
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        } else {
            return $this->render('contact', [
                'model' => $model,
            ]);
        }
    }

    /**
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    /**
     * Change password
     * POST web_app_base_url/changePassword
     * Consumes: APPLICATION_JSON
     * Produces: APPLICATION_JSON
     * Body parameters: oldPassword, newPassword
     * Headers: token
     *
     * @return string|\yii\web\Response
     */
    public function actionChangePassword()
    {
        if (!Help::isLogIn()) {
            return $this->goHome();
        }

        $model = new AccountForm();
        $model->scenario = 'change';

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $client = new Client();
            try {
                $res = $client->post(Yii::$app->params['apiUrl'] . 'changePassword', [
                    'body' => Json::encode([
                        'oldPassword' => $model->old_password,
                        'newPassword' => $model->new_password
                    ]),
                    'headers' => [
                        'content-type' => 'application/json',
                        'token' => Yii::$app->session['token'],
                    ]
                ]);
//                echo $res->getBody();
                \Yii::$app->getSession()->setFlash('success', 'Parola a fost schimbata cu succes!');
            } catch (ClientException $e) {
                $error = $e->getResponse()->getBody()->getContents();
                \Yii::$app->getSession()->setFlash('error', $error);
            }
        }

        return $this->render('account-change-password', [
            'model' => $model,
        ]);

    }

    /**
     * Forgot password (user clicks forgot password button) - iti trimite pe mail cod pt reset
     * POST web_app_base_url/forgotPassword
     * Consumes: APPLICATION_JSON
     * Produces: APPLICATION_JSON
     * Body parameters: email
     *
     * @return string
     */
    public function actionForgotPassword()
    {
        $model = new AccountForm();
        $model->scenario = 'forgot';

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $client = new Client();
            try {
                $res = $client->post(Yii::$app->params['apiUrl'] . 'forgotPassword', [
                    'body' => Json::encode(['email' => $model->email]),
                    'headers' => [
                        'content-type' => 'application/json'
                    ]
                ]);
//                echo $res->getBody();
                \Yii::$app->getSession()->setFlash('success',
                    'Parola a fost resetata cu succes! Verifica-ti email-ul!');
            } catch (ClientException $e) {
                $error = $e->getResponse()->getBody()->getContents();
                \Yii::$app->getSession()->setFlash('error', $error);
            }
        }

        return $this->render('account-forgot-password', [
            'model' => $model,
        ]);
    }

    /**
     * Forgot password (user entered code with new password)
     * POST web_app_base_url/updatePassword
     * Consumes: APPLICATION_JSON
     * Produces: APPLICATION_JSON
     * Body parameters: email, password, code
     *
     * @return string
     */
    public function actionUpdatePassword()
    {
        $model = new AccountForm();
        $model->scenario = 'update';

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $client = new Client();
            try {
                $res = $client->post(Yii::$app->params['apiUrl'] . 'updatePassword', [
                    'body' => Json::encode([
                        'email' => $model->email,
                        'password' => $model->new_password,
                        'code' => $model->code,
                    ]),
                    'headers' => [
                        'content-type' => 'application/json'
                    ]
                ]);
//                echo $res->getBody();
                \Yii::$app->getSession()->setFlash('success',
                    'Parola a fost schimbata cu succes!<br/>Te poti conecta folosind noua parola!');
            } catch (ClientException $e) {
                $error = $e->getResponse()->getBody()->getContents();
                \Yii::$app->getSession()->setFlash('error', $error);
            }
        }

        return $this->render('account-update-password', [
            'model' => $model,
        ]);
    }

    /**
     * GET web_app_base_url/profile
     * Headers: token, device_token
     * Response Body example:
     * { "data": {
     *       "userProfile": {
     *          "address": "address",
     *           "cnp": "1234567891231",
     *           "dateOfBirth": "2015-12-06T12:26:10",
     *           "firstName": "First Name",
     *           "lastName": "Last Name",
     *           "socialId": "AA123456"
     *       }
     *    }
     * }
     *
     * @return string
     */
    public function actionViewProfile()
    {
        if (!Help::isLogIn()) {
            return $this->goHome();
        }

        $model = ProfileForm::profileData();
        if (@$model['message']) {
            $this->redirect('upload-profile');
        }

        return $this->render('view-profile', [
            'model' => $model,
        ]);
    }

    /**
     * Update profile
     * POST http://lr-eb-api-dev.elasticbeanstalk.com/api/profile
     * Consumes: multipart/form-data
     * Produces: application/json
     * Body parameters: firstName, lastName, cnp, address(optional), socialId, dateOfBirth(optional), image
     * NOTE: dateOfBirth format: yyyy-MM-dd'T'HH:mm:ss.SSS or yyyy-MM-dd'T'HH:mm:ss
     * NOTE: all parameters should be form data parameters, image is file
     *
     * Response Body example:
     * { "data": {
     *       "userProfile": {
     *          "address": "address",
     *           "cnp": "1234567891231",
     *           "dateOfBirth": "2015-12-06T12:26:10",
     *           "firstName": "First Name",
     *           "lastName": "Last Name",
     *           "socialId": "AA123456"
     *       }
     *    }
     * }
     *
     * @return string
     */
    public function actionUpdateProfile()
    {
        if (!Help::isLogIn()) {
            return $this->goHome();
        }

        if (Yii::$app->session['OCR']) {
            $model = $this->getProfileFromJson(json_decode(Yii::$app->session['OCR']));
            unset(Yii::$app->session['OCR']);
        } else {
            $model = $this->getProfileData();
        }
        $errors = [];
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $client = new Client();
            try {
                $res = $client->post(Yii::$app->params['apiUrl'] . 'profile', [
                    'body' => [
                        'firstName' => $model->firstName,
                        'lastName' => $model->lastName,
                        'cnp' => $model->cnp,
                        'address' => $model->address,
                        'socialId' => $model->socialId,
                        'dateOfBirth' => $model->dateOfBirth . 'T' . date('h:m:s'),
                        new \GuzzleHttp\Post\PostFile('image', @fopen(@$_FILES['ProfileForm']['tmp_name']['image'], 'r') ? :''),
                    ],
                    'headers' => ['token' => Yii::$app->session['token'],]
                ]);
//                die($res->getBody()->getContents());
                $this->redirect('view-profile');
            } catch (ClientException $e) {
                print_r($e->getResponse()->getBody()->getContents());
                die('dsa');
            }
        }

        return $this->render('update-profile', [
            'model' => $model,
            'errors' => $errors,
        ]);
    }

    /**
     * @return string
     */
    public function actionUploadProfile()
    {
        if (!Help::isLogIn()) {
            return $this->goHome();
        }

        $model = new UploadForm();

        if ($model->load(Yii::$app->request->post()) &&
            ($model->image = UploadedFile::getInstance($model, 'image')) && $model->validate()
        ) {
            $imageName = \Yii::$app->session['userId'] . time() . '.jpg';
            $model->image->saveAs($imageName);
            exec('curl -H "Content-Type:application/octet-stream" -H "token:' . Yii::$app->session['token'] .
                '" --data-binary @' . getcwd() . DIRECTORY_SEPARATOR . $imageName . ' ' .
                \Yii::$app->params['apiUrl'] . 'profileImageRecognition', $result);
            \Yii::$app->session['OCR'] = $result[0];
            $this->redirect('update-profile');
        } else {
            return $this->render('upload-profile', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Get Score History
     * GET web_app_base_url/scoreHistory
     * Produces: APPLICATION_JSON
     * Headers: token
     * Response Body example:
     * {
     * "data": {
     *      "scoreHistory": [
     *          {
     *              "date": "2015-03-30T12:35:21",
     *              "score": 522,
     *              "recordId": 5
     *          },
     *          {
     *              "date": "2015-03-30T12:35:22",
     *              "score": 522,
     *              "recordId": 6
     *          },
     *      ]
     *  }
     * }
     *
     * @return string|mixed
     */
    public function actionHistory()
    {
        if (!Help::isLogIn()) {
            return $this->goHome();
        }

        $client = new Client();
        try {
            $res = $client->get(Yii::$app->params['apiUrl'] . 'scoreHistory', [
                'headers' => ['token' => Yii::$app->session['token']]
            ]);

            $models = Json::decode($res->getBody()->getContents());

            $data = isset($models['data']['scoreHistory']) ? $models['data']['scoreHistory'] : [];

            $dataProvider = new ArrayDataProvider([
                'allModels' => $data,
                'pagination' => false, // ['pageSize' => Yii::$app->params['pageSize']],
                'sort' => [
                    'attributes' => [
                        'date',
                        'score',
                        'recordId'
                    ],
                    'defaultOrder' => ['recordId' => SORT_DESC]
                ],
            ]);

            $morisData = $this->getMorisJsonData($dataProvider->getModels());

        } catch (ClientException $e) {
            print_r($e->getResponse()->getBody()->getContents());
            die('error');
        }

        return $this->render('history', [
            'dataProvider' => $dataProvider,
            'morisData' => $morisData,
        ]);
    }

    /**
     * Get Credit Bureau Score Last Saved
     * GET http://lr-eb-api-dev.elasticbeanstalk.com/api/score
     * Produces: application/json
     * Headers: token, device_token
     * Note: If there is data in db then latest data will be returned otherwise empty data will be returned. No request to Credit Bureau will be done.
     * Response Body example:
     * {"data": {
     *      "briefScoreData": {
     *      "closedAccounts": 0,
     *      "delinguentAccounts": 4,
     *      "derogatoryAccounts": 0,
     *      "lastUpdated": "2015-04-01T11:55:50",
     *      "monthlyPayment": 887,
     *      "openAccounts": 0,
     *      "scoreInfo": {
     *          "averageCreditScoreCumulative": 8.2,
     *          "averageCreditScoreTotal": 5.1,
     *          "creditScore": 522
     *      },
     *      "totalAccountNumber": 4,
     *      "totalBalance": 595
     * }}
     */
    public function actionScore()
    {
        if (!Help::isLogIn()) {
            return $this->goHome();
        }

        $client = new Client();
        try {
            $res = $client->get(Yii::$app->params['apiUrl'] . 'score', [
                'body' => \yii\helpers\Json::encode([
                    'userId' => \Yii::$app->session['userId'],// Yii::$app->user->identity->user_id,
                ]),
                'headers' => [
                    'content-type' => 'application/json',
                    'token' => \Yii::$app->session['token'],
                ]
            ]);

            $model = Json::decode($res->getBody()->getContents());

        } catch (ServerException $e) {
//            VarDumper::dump($e->getResponse()->getBody()->getContents());
//            Yii::$app->end();
            $model = [];
        }

        if (!isset($model['data']['briefScoreData']['scoreInfo'])) {
            $this->redirect(['site/upload-profile']);
        }

        return $this->render('score-new', [
            'model' => $model
        ]);
    }

    /**
     * Re-check score
     * Get Credit Bureau Score From CB Service
     * GET web_app_base_url/cbscore
     * Produces: APPLICATION_JSON
     * Headers: token, latitude, longitude, device_token
     * Note: A request to Credit Bureau service will be send.
     * Response Body example:
     * {"data": {
     *      "briefScoreData": {
     *      "closedAccounts": 0,
     *      "delinguentAccounts": 4,
     *      "derogatoryAccounts": 0,
     *      "lastUpdated": "2015-04-01T11:55:50",
     *      "monthlyPayment": 887,
     *      "openAccounts": 0,
     *      "scoreInfo": {
     *          "averageCreditScoreCumulative": 8.2,
     *          "averageCreditScoreTotal": 5.1,
     *          "creditScore": 522
     *      },
     *      "totalAccountNumber": 4,
     *      "totalBalance": 595
     * }}
     */
    public function actionCbscore()
    {
        if (!Help::isLogIn()) {
            return $this->goHome();
        }

        $credit = (int)Help::getCreditInfo();

        if ($credit < Help::MIN_CREDIT_FOR_RECHECK) {
            $model['error_msg'] = Yii::t('app', 'You do not have enough credit. You must buy coins.');
        } else {
            $client = new Client();
            try {
                $res = $client->get(Yii::$app->params['apiUrl'] . 'cbscore', [
                    'headers' => [
                        'content-type' => 'application/json',
                        'token' => Yii::$app->session['token']
                    ]
                ]);

                $model = Json::decode($res->getBody()->getContents());

            } catch (ClientException $e) {
                $errorMsg = Yii::t('app', 'An error occurred!');

                $data = Json::decode($e->getResponse()->getBody()->getContents());

                return $this->render('error', [
                    'message' => isset($data['message']) ? Yii::t('app', "{$data['message']}") : $errorMsg,
                    'name' => $errorMsg,
                ]);
            }
        }

        return $this->render('cbscore', [
            'model' => $model,
        ]);
    }

    /**
     * Get Notification
     * GET http://lr-eb-api-dev.elasticbeanstalk.com/api/notification
     * Produces: application/json
     * Headers: token
     *
     * @return string
     * @throws \yii\base\ExitException
     */
    public function actionNotifications()
    {
        if (!Help::isLogIn()) {
            return $this->goHome();
        }

        $client = new Client();
        try {
            $res = $client->get(Yii::$app->params['apiUrl'] . 'notification', [
                'headers' => [
                    'content-type' => 'application/json',
                    'token' => Yii::$app->session['token']
                ]
            ]);

            $models = Json::decode($res->getBody()->getContents());

            $data = isset($models['data']['notifications']) ? $models['data']['notifications'] : [];

            $dataProvider = new ArrayDataProvider([
                'allModels' => $data,
                'pagination' => false, // ['pageSize' => Yii::$app->params['pageSize']],
                'sort' => [
                    'attributes' => [
                        'dateCreated',
                        'id',
                        'message',
                        'read'
                    ],
                    'defaultOrder' => ['id' => SORT_DESC]
                ],
            ]);

        } catch (ClientException $e) {
            VarDumper::dump($e->getResponse()->getBody()->getContents());
            Yii::$app->end();
        }

        return $this->render('notifications', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Mark Notification As Read
     * POST http://lr-eb-api-dev.elasticbeanstalk.com/api/notification/markread
     * Consumes: application/json
     * Body parameters: notificationId
     * NOTE: notificationId should be number
     * Headers: token
     *
     * @param $notificationId
     * @throws \yii\base\ExitException
     *
     * @param $notificationId
     * @return \yii\web\Response
     * @throws \yii\base\ExitException
     */
    public function actionNotificationRead($notificationId)
    {
        if (!Help::isLogIn()) {
            return $this->goHome();
        }

        $client = new Client();
        try {
            $res = $client->post(Yii::$app->params['apiUrl'] . 'notification/markread', [
                'body' => Json::encode([
                    'notificationId' => $notificationId
                ]),
                'headers' => [
                    'content-type' => 'application/json',
                    'token' => Yii::$app->session['token']
                ]
            ]);

            echo 'ok';

        } catch (ClientException $e) {
//            print_r($e->getResponse()->getBody()->getContents());
            echo 'error';
            exit;
        }
        Yii::$app->end();
    }

    /**
     * Get Credit Bureau Score For Particular Date
     * POST web_app_base_url/scoreForDate
     * Produces: APPLICATION_JSON
     * Body parameters: userId, recordId
     * NOTE: userId - String, recordId - Number that should be taken from scoreHistory response
     * Headers: token
     * Response Body example:
     * {"data": {
     *      "briefScoreData": {
     *          "closedAccounts": 0,
     *          "delinguentAccounts": 4,
     *          "derogatoryAccounts": 0,
     *          "lastUpdated": "2015-04-01T11:55:50",
     *          "monthlyPayment": 887,
     *          "openAccounts": 0,
     *          "scoreInfo": {
     *                   "averageCreditScoreCumulative": 8.2,
     *                   "averageCreditScoreTotal": 5.1,
     *                   "creditScore": 522
     *          },
     *          "totalAccountNumber": 4,
     *          "totalBalance": 595
     *      }
     * }
     *
     * @param $recordId
     * @return mixed
     */
    public function actionScoreForDate($recordId)
    {
        if (!Help::isLogIn()) {
            return $this->goHome();
        }

        $client = new Client();
        try {
            $res = $client->post(Yii::$app->params['apiUrl'] . 'scoreForDate', [
                'body' => Json::encode([
                    'userId' => Yii::$app->session['userId'],
                    'recordId' => $recordId
                ]),
                'headers' => [
                    'content-type' => 'application/json',
                    'token' => Yii::$app->session['token']
                ]
            ]);

            $model = Json::decode($res->getBody()->getContents());

        } catch (ClientException $e) {
            VarDumper::dump($e->getResponse()->getBody()->getContents());
            Yii::$app->end();
        }

        return $this->render('score-for-date', [
            'model' => $model,
        ]);
    }

    /**
     * Send Credit Report
     * GET web_app_base_url/scoreReport
     * Produces: APPLICATION_JSON
     * Headers: token
     */
    public function actionScoreReport()
    {
        if (\Yii::$app->request->isAjax) {
            $client = new Client();
            try {
                $res = $client->get(Yii::$app->params['apiUrl'] . 'scoreReport', [
                    'headers' => [
                        'content-type' => 'application/json',
                        'token' => Yii::$app->session['token']
                    ]
                ]);
                //$model = Json::decode($res->getBody()->getContents());

            } catch (ClientException $e) {
                echo 'error';
//                print_r($e->getResponse()->getBody()->getContents());
//                die('error');
            }

            echo 'ok';

        }
    }

    /**
     * Ajax handler for language change dropdown list. Sets cookie ready for next request
     */
    public function actionLanguage()
    {
        if (\Yii::$app->request->isAjax) {
            if (Yii::$app->request->post('_lang') !== null && array_key_exists(Yii::$app->request->post('_lang'),
                    Yii::$app->params['languages'])
            ) {
                Yii::$app->language = Yii::$app->request->post('_lang');
                $cookie = new Cookie([
                    'name' => '_lang',
                    'value' => Yii::$app->request->post('_lang'),
                ]);
                Yii::$app->getResponse()->getCookies()->add($cookie);
            }
            Yii::$app->end();
        }
    }

    /**
     * @return string
     */
    public function actionWebcam()
    {
        if (!Help::isLogIn()) {
            return $this->goHome();
        }

        $model = new UploadForm;

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {

            $encodedData = $_POST['UploadForm']['image'];
            $binaryData = base64_decode($encodedData);
            $imageName = \Yii::$app->session['userId'] . time() . '.jpg';

            // save to server
            $img = file_put_contents($imageName, $binaryData);
            if (!$img) {
                die("Could not save image!  Check file permissions.");
            } else {
                exec('curl -H "Content-Type:application/octet-stream" -H "token:' . \Yii::$app->session['token'] .
                    '" --data-binary @' . getcwd() . DIRECTORY_SEPARATOR . $imageName . ' ' .
                    \Yii::$app->params['apiUrl'] . 'profileImageRecognition', $result);

                Yii::$app->session['OCR'] = isset($result[0]) ? $result[0] : null;

                $this->redirect('update-profile');
            }
        }

        return $this->render('webcam', [
            'model' => $model
        ]);
    }

    /**
     * @return string
     */
    public function actionMobilPay()
    {
        if (!Help::isLogIn()) {
            return $this->goHome();
        }

        $model = ProfileForm::profileData();
        $billing = new MobilPayBillingForm();

        if (isset($model['data']['userProfile'])) {
            $billing->firstName = $model['data']['userProfile']['firstName'];
            $billing->lastName = $model['data']['userProfile']['lastName'];
            $billing->fiscal_number = $model['data']['userProfile']['cnp'];
            $billing->identity_number = $model['data']['userProfile']['socialId'];
            $billing->address = $model['data']['userProfile']['address'];
        }

        $billing->coin = $_GET['coins'];

        if ($billing->load(Yii::$app->request->post()) && $billing->validate()) {

//            echo '<pre>';
//            print_r($billing);
//            exit;

            try {
                srand((double)microtime() * 1000000);
                $objPmReqCard = new Mobilpay_Payment_Request_Card();

                # merchant account signature - generated by mobilpay.ro for every merchant account
                # semnatura contului de comerciant - mergi pe www.mobilpay.ro
                # Admin -> Conturi de comerciant -> Detalii -> Setari securitate
                $objPmReqCard->signature = Yii::$app->params['mobilPay']['signature'];

                # you should assign here the transaction ID registered by your application for this commercial operation
                # order_id should be unique for a merchant account
                $amountPaid = $billing->coin;
                $amountCoinArray = ArrayHelper::map(Yii::$app->params['coins'], 'suma', 'coin');
                $coinsNo = $amountCoinArray[$billing->coin];

                // $orderId = userId _ token _ suma _ nrCoins _ time()
                $objPmReqCard->orderId = Yii::$app->session['userId'] . '_' . Yii::$app->session['token'] . '_' . $amountPaid . '_' . $coinsNo . '_' . time();

                // extra params
                $objPmReqCard->params = [
                    'userId' => Yii::$app->session['userId'],
                    'token' => Yii::$app->session['token'],
                    'amount' => $amountPaid,
                    'coins' => $coinsNo,
                ];

                # below is where mobilPay will send the payment result. This URL will always be called first; mandatory
                $objPmReqCard->confirmUrl = Yii::$app->params['mobilPay']['confirmUrl'];

                # below is where mobilPay redirects the client once the payment process is finished.
                # Not to be mistaken for a "successURL" nor "cancelURL"; mandatory
                $objPmReqCard->returnUrl = Yii::$app->params['mobilPay']['returnUrl'];

                # detalii cu privire la plata: moneda, suma, descrierea
                # payment details: currency, amount, description
                $objPmReqCard->invoice = new Mobilpay_Payment_Invoice();

                # payment currency in ISO Code format; permitted values are RON, EUR, USD, MDL;
                # please note that unless you have mobilPay permission to
                # process a currency different from RON, a currency exchange will occur from your currency to RON,
                # using the official BNR exchange rate from that moment
                # and the customer will be presented with the payment amount in a dual currency in the payment page, i.e N.NN RON (e.ee EUR)
                $objPmReqCard->invoice->currency = Yii::$app->params['currency'];
                $objPmReqCard->invoice->amount = $billing->coin;

                # available installments number; if this parameter is present, only its value(s) will be available
//                 $objPmReqCard->invoice->installments= '2,3';

                # selected installments number; its value should be within the available installments defined above
//                 $objPmReqCard->invoice->selectedInstallments= '3';

                # platile ulterioare vor contine in request si informatiile despre token.
                # Prima plata nu va contine linia de mai jos.
//                $objPmReqCard->invoice->tokenId = 'token_id';
//                $objPmReqCard->invoice->details = 'Plata cu card-ul prin mobilPay';

                # detalii cu privire la adresa posesorului cardului
                # details on the cardholder address (optional)
                $billingAddress = new Mobilpay_Payment_Address();
                $billingAddress->type = $billing->type; //should be "person"
                $billingAddress->firstName = $billing->firstName;
                $billingAddress->lastName = $billing->lastName;
                $billingAddress->address = $billing->address;
                $billingAddress->email = $billing->email;
                $billingAddress->mobilePhone = $billing->mobilePhone;
                $objPmReqCard->invoice->setBillingAddress($billingAddress);

                # detalii cu privire la adresa de livrare
                # details on the shipping address
//            $shippingAddress = new Mobilpay_Payment_Address();
//                $shippingAddress->type = $_POST['shipping_type'];
//                $shippingAddress->firstName = $_POST['shipping_first_name'];
//                $shippingAddress->lastName = $_POST['shipping_last_name'];
//                $shippingAddress->address = $_POST['shipping_address'];
//                $shippingAddress->email = $_POST['shipping_email'];
//                $shippingAddress->mobilePhone = $_POST['shipping_mobile_phone'];
//            $objPmReqCard->invoice->setShippingAddress($shippingAddress);

                # uncomment the line below in order to see the content of the request
                echo "<pre>";
                print_r($objPmReqCard);
                echo "</pre>";
                $objPmReqCard->encrypt(Yii::$app->params['mobilPay']['x509FilePath']);

            } catch (Exception $e) {
                echo "<pre>";
                echo $e->getMessage();
                echo "<br>";
                exit('Exception');
            }

            return $this->render('mobil-redirect', [
                'paymentUrl' => Yii::$app->params['mobilPay']['paymentUrl'] . DIRECTORY_SEPARATOR . Yii::$app->language,
                'objPmReqCard' => $objPmReqCard,
            ]);

        } else {
            return $this->render('mobil-pay', [
                'billing' => $billing,
            ]);
        }
    }

    /**
     * Below is where mobilPay will send the payment result.
     * This URL will always be called first; mandatory
     */
    public function actionMobilPayConfirm()
    {
//        $ansData = 'actionMobilPayConfirm:' . PHP_EOL . serialize($_REQUEST);
//        $logFile = Yii::$app->user->identity->id . '_' . time() . '.txt';
//
//        // log
//        file_put_contents($logFile, $ansData);

        $errorType = Mobilpay_Payment_Request_Abstract::CONFIRM_ERROR_TYPE_NONE;
        $errorCode = 0;
        $errorMessage = '';

        if (strcasecmp($_SERVER['REQUEST_METHOD'], 'post') == 0) {
            if (isset($_POST['env_key']) && isset($_POST['data'])) {

                # calea catre cheia privata
                $privateKeyFilePath = Yii::$app->params['mobilPay']['privateKeyFilePath'];

                try {
                    $objPmReq = Mobilpay_Payment_Request_Abstract::factoryFromEncrypted($_POST['env_key'],
                        $_POST['data'], $privateKeyFilePath);

                    # uncomment the line below in order to see the content of the request
                    // print_r($objPmReq);
                    $errorCode = $objPmReq->objPmNotify->errorCode;

                    # action = status only if the associated error code is zero
                    if ($errorCode == "0") {

                        # orice action este insotit de un cod de eroare si de un mesaj de eroare. Acestea pot fi citite folosind
                        # $cod_eroare = $objPmReq->objPmNotify->errorCode; respectiv
                        # $mesaj_eroare = $objPmReq->objPmNotify->errorMessage;
                        # pentru a identifica ID-ul comenzii pentru care primim rezultatul platii folosim
                        # $id_comanda = $objPmReq->orderId;
                        switch ($objPmReq->objPmNotify->action) {
                            case 'confirmed':
                                # cand action este confirmed avem certitudinea ca banii au plecat din contul posesorului
                                # de card si facem update al starii comenzii si livrarea produsului
                                # update DB, SET status = "confirmed/captured"

                                if ($this->updateCredit($objPmReq->orderId)) {
                                    // sa facut update la credit
                                } else {
                                    // erore
                                    $errorCode = 1;
                                    $errorType = 1;
                                    $errorMessage = 'Nu sa putut face update la credit!';
                                }
                                break;
                            case 'confirmed_pending':
                                # cand action este confirmed_pending inseamna ca tranzactia este in curs de verificare antifrauda.
                                # Nu facem livrare/expediere. In urma trecerii de aceasta verificare se va primi o noua notificare pentru o
                                # actiune de confirmare sau anulare.
                                # update DB, SET status = "pending"

                                break;
                            case 'paid_pending':
                                # cand action este paid_pending inseamna ca tranzactia este in curs de verificare.
                                # Nu facem livrare/expediere. In urma trecerii de aceasta verificare se va primi o noua notificare pentru
                                # o actiune de confirmare sau anulare.
                                # update DB, SET status = "pending"

                                break;
                            case 'paid':
                                # cand action este paid inseamna ca tranzactia este in curs de procesare. Nu facem livrare/expediere.
                                # In urma trecerii de aceasta procesare se va primi o noua notificare pentru o actiune de confirmare sau anulare.
                                # update DB, SET status = "open/preauthorized"

                                break;
                            case 'canceled':
                                # cand action este canceled inseamna ca tranzactia este anulata. Nu facem livrare/expediere.
                                # update DB, SET status = "canceled"

                                break;
                            case 'credit':
                                # cand action este credit inseamna ca banii sunt returnati posesorului de card.
                                # Daca s-a facut deja livrare, aceasta trebuie oprita sau facut un reverse.
                                # update DB, SET status = "refunded"

                                break;
                            default:
                                $errorType = Mobilpay_Payment_Request_Abstract::CONFIRM_ERROR_TYPE_PERMANENT;
                                $errorCode = Mobilpay_Payment_Request_Abstract::ERROR_CONFIRM_INVALID_ACTION;
                                $errorMessage = Yii::t('app', 'Mobilpay_refference_action paramaters is invalid!');
                                break;
                        }
                    } else {
                        # update DB, SET status = "rejected"

                    }
                } catch (Exception $e) {
                    $errorType = Mobilpay_Payment_Request_Abstract::CONFIRM_ERROR_TYPE_TEMPORARY;
                    $errorCode = $e->getCode();
                    $errorMessage = $e->getMessage();
                }
            } else {
                $errorType = Mobilpay_Payment_Request_Abstract::CONFIRM_ERROR_TYPE_PERMANENT;
                $errorCode = Mobilpay_Payment_Request_Abstract::ERROR_CONFIRM_INVALID_POST_PARAMETERS;
                $errorMessage = Yii::t('app', 'mobilpay.ro posted invalid parameters!');
            }
        } else {
            $errorType = Mobilpay_Payment_Request_Abstract::CONFIRM_ERROR_TYPE_PERMANENT;
            $errorCode = Mobilpay_Payment_Request_Abstract::ERROR_CONFIRM_INVALID_POST_METHOD;
            $errorMessage = Yii::t('app', 'Invalid request method for payment confirmation!');
        }

        header('Content-type: application/xml');
        echo "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";
        if ($errorCode == 0) {
            echo "<crc>{$errorMessage}</crc>";
        } else {
            echo "<crc error_type=\"{$errorType}\" error_code=\"{$errorCode}\">{$errorMessage}</crc>";
        }
        Yii::$app->end();
    }

    /**
     * Below is where mobilPay redirects the client once the payment process is finished.
     * Not to be mistaken for a "successURL" nor "cancelURL"; mandatory
     */
    public function actionMobilPayReturn()
    {
        $ansData = 'actionMobilPayReturn:' . PHP_EOL . serialize($_REQUEST);
        $logFile = Yii::$app->session['userId'] . '_' . time() . '.txt';

        // log
        file_put_contents($logFile, $ansData);
    }

    /**
     * Update amount of available interrogation units
     * POST http://lr-eb-api-dev.elasticbeanstalk.com/api/interrogations
     * Consumes: application/json
     * Produces: application/json
     * Body parameters: userId, interrogationsAmout
     * NOTE: interrogationsAmout - should be number
     * Headers: token
     * Response Body example:
     * {  "data": {
     *      "userInterrogations": {
     *      "interrogationsAmout": 0,
     *      "userId": "a57da41f-3d10-4d04-bed9-2f42a11e79ad"
     * } }}
     *
     * @param $orderId
     * @return bool
     */
    private function updateCredit($orderId)
    {
        // $orderId = userId _ token _ suma _ nrCoins _ time()
        $orderDataArray = explode('_', $orderId);
        $userId = $orderDataArray[0];
        $token = $orderDataArray[1];
        $coinsNo = $orderDataArray[3];

        $actualCredit = Help::getCreditInfo();
        if (is_null($actualCredit)) {
            return false;
        }

        // suma sau coin ?
        $interrogationsAmout = $coinsNo + $actualCredit;

        $client = new Client();
        try {
            $res = $client->get(Yii::$app->params['apiUrl'] . 'interrogations', [
                'body' => Json::encode([
                    'userId' => $userId,
                    'interrogationsAmout' => $interrogationsAmout
                ]),
                'headers' => [
                    'content-type' => 'application/json',
                    'token' => $token
                ]
            ]);

//            $model = Json::decode($res->getBody()->getContents());
            return true;

        } catch (ClientException $e) {
//            $model = $e->getResponse()->getBody()->getContents();
            return false;
        }
    }

    /**
     * @param $data
     * @return string
     */
    private function getMorisJsonData($data)
    {
        $morisData = '[';
        foreach ($data as $key => $val) {
            $date = date('Y-m-d', strtotime($val['date']));
            $scor = isset($val['score']) ? $val['score'] : 0;
            $morisData .= "{date: '" . $date . "', scor: '" . $scor . "'},";
        }
        $morisData .= ']';

        return $morisData;
    }

    /**
     * @return ProfileForm
     */
    private function getProfileData()
    {
        $data = ProfileForm::profileData();

        if (isset($data['message'])) {
            // fixme - daca sunt errori
        } elseif (isset($data['data']['userProfile'])) {
            $model = new ProfileForm();

            $model->address = isset($data['data']['userProfile']['address']) ? $data['data']['userProfile']['address'] : null;
            $model->cnp = isset($data['data']['userProfile']['cnp']) ? $data['data']['userProfile']['cnp'] : null;
            $model->dateOfBirth = isset($data['data']['userProfile']['dateOfBirth']) ? $data['data']['userProfile']['dateOfBirth'] : null;
            $model->firstName = isset($data['data']['userProfile']['firstName']) ? $data['data']['userProfile']['firstName'] : null;
            $model->lastName = isset($data['data']['userProfile']['lastName']) ? $data['data']['userProfile']['lastName'] : null;
            $model->socialId = isset($data['data']['userProfile']['socialId']) ? $data['data']['userProfile']['socialId'] : null;
            $model->image = isset($data['data']['userProfile']['image']) ? $data['data']['userProfile']['image'] : null;

            return $model;
        }
        return new ProfileForm();
    }

    /**
     * @param $data
     * @return ProfileForm
     */
    private function getProfileFromJson($data)
    {
        error_reporting(0);
        $model = new ProfileForm();
        $find = ['i', 'I', 'o', 'O'];
        $replace = ['1', '1', '0', '0'];

        $model->cnp = str_replace($find, $replace, $data->data->userProfile->cnp);
        $model->firstName = str_replace($replace, $find, $data->data->userProfile->firstName);
        $model->lastName = str_replace($replace, $find, $data->data->userProfile->lastName);
        $model->socialId = $data->data->userProfile->socialId;
        if ($cnp = $model->cnp) {
            $year = in_array($cnp[0], [1, 2]) ? 19 : 20;
            $model->dateOfBirth = sprintf('%s-%s-%sT00:00:00',
                $year . substr($cnp, 1, 2),
                substr($cnp, 3, 2),
                substr($cnp, 5, 2)
            );
        }
        return $model;
    }


    public function actionTest()
    {
        $client = new Client();
        try {
            $res = $client->post(Yii::$app->params['apiUrl'] . 'profileImageRecognition', [
                'body' => [
                    'a' => file_get_contents('1.jpg'),
                ],
                'headers' => [
                    'content-type' => 'application/octet-stream',
                    'token' => '6ef2ea8a-3721-494b-a5cc-49356cefcb24',
                ]
            ]);
            echo $res->getBody();
        } catch (\GuzzleHttp\Exception\ServerException $e) {
            print_r($e->getResponse()->getBody()->getContents());
            die('sda');
        }
    }
    public function actionTest1()
    {
        exec('curl -H "Content-Type:application/octet-stream" -H "token:6ef2ea8a-3721-494b-a5cc-49356cefcb24" --data-binary @/home/puiu/www/scorerise/web/60363.jpg http://lr-eb-api-dev.elasticbeanstalk.com/api/profileImageRecognition',
            $result);
        var_dump($result);
        die('dsa');
    }
}
