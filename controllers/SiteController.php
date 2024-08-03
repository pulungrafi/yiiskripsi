<?php

namespace app\controllers;

use Yii;
use yii\db\ActiveRecord;
use yii\filters\AccessControl;
use app\controllers\BaseController;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\BcsImage;
use app\models\BodyCountScore;
use app\models\Cage;
use app\models\ContactForm;
use app\models\EditProfileForm;
use app\models\Livestock;
use app\models\LivestockImage;
use app\models\LoginForm;
use app\models\Note;
use app\models\NoteImage;
use app\models\RegisterForm;
use app\models\RequestPasswordResetForm;
use app\models\User;



class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
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
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $livestock = Livestock::find()->all();
        $cage = Cage::find()->all();

        return $this->render('index', [
            'sapi' => count($livestock),
            'cage' => count($cage),
        ]);
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }
    
    public function actionNotes(){
        return $this->render('notes');
    }
    
    public function actionCreateSapi(){
        $model = new Livestock();
        $requestData = Yii::$app->getRequest()->getBodyParams();
        $model->load($requestData, '');

        // Validasi cage_id berdasarkan user_id
        $cageId = $model->cage_id;
        $userId = 8;
        // $userId = Yii::$app->user->identity->id;

        if ($cageId === null) {
            Yii::$app->getResponse()->setStatusCode(400); // Bad Request
            return $this -> render('error',[
                'message' => 'Kandang tidak boleh kosong, mohon buat kandang terlebih dahulu.',
                'error' => true,
            ]);
        }
    
        $existingCage = Cage::find()
            ->where(['id' => $cageId, 'user_id' => $userId])
            ->exists();
    
        if (!$existingCage) {
            Yii::$app->getResponse()->setStatusCode(400); // Bad Request
            return [
                'message' => 'Kandang tidak ditemukan, mohon buat kandang sebelum menambahkan ternak.',
                'error' => true,
            ];
        }

        if ($model->save()) {
            Yii::$app->getResponse()->setStatusCode(201);
            return $this-> render('create-sapi',[
                'message' => 'Data ternak berhasil dibuat.',
                'error' => false,
                'data' => $model,
            ]);
        } else {
            Yii::$app->getResponse()->setStatusCode(400);
            return [
                'message' => 'Gagal membuat data ternak.',
                'error' => true,
                'details' => $this->getValidationErrors($model),
            ];
        }
    }
    
    public function actionCreateKandang(){
        $cage = new Cage();
        $cage->user_id = 2;
        //$cage->user_id = Yii::$app->user->id;
        if ($cage->load(Yii::$app->request->post()) && $cage->save()) {
            Yii::$app->response->statusCode = 201;
            return $this -> render('create-kandang',[
                'message' => 'Kandang berhasil dibuat',
                'error' => false,
                'data' => $cage,
            ]);
            
        }
        // else {
        //     Yii::$app->response->statusCode = 400;
        //     return [
        //         'message' => 'Gagal membuat kandang', 
        //         'error' => true, 
        //         'details' => $this->getValidationErrors($cage),
        //     ];}
        return $this->render('create-kandang', [
            'cage' => $cage,
        ]);

    }
    
    public function actionRegister(){
        return $this->render('register');
    }
    
    public function actionBcs(){
        return $this->render('bcs');
    }
}
