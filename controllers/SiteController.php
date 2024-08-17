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
use yii\helpers\Url;



class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public $enableCsrfValidation = false;

     public function behaviors()
    {
        $behaviors = parent::behaviors();
        
        // $behaviors['access'] = [
        //         'class' => AccessControl::className(),
        //         'only' => ['logout'],
        //         'rules' => [
        //             [
        //                 'actions' => ['logout'],
        //                 'allow' => true,
        //                 'roles' => ['@'],
        //             ],
        //         ],
        //     ];
        // $behaviors['verbs'] = [
        //     'class' => VerbFilter::className(),
        //     'actions' => [
        //         'logout' => ['post'],
        //      ],
        // ];
            return $behaviors;
    }
    public function getValidationErrors($model)
    {
        $errorDetails = [];
        foreach ($model->errors as $errors) {
            foreach ($errors as $error) {
                $errorDetails[] = $error;
            }
        }
        return $errorDetails;
    }
    protected $noAuthActions = [];

    public function beforeAction($action)
    {
        if (!parent::beforeAction($action)) {
            return false;
        }

        // CEK PENGGUNAA DISINI HARUS TIDAK GUEST

        return true;
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
        if (Yii::$app->user->isGuest) {
            $redirect = Url::to(['user/index']);
            $model = new LoginForm();
            return $this-> render($redirect , ['model'=> $model]);
 
         }
        else{$userId = Yii::$app->user->id;

            $livestockCount = Livestock::find()->where(['user_id' => $userId])->count();
            $cageCount = Cage::find()->where(['user_id' => $userId])->count();
            $cages = Cage::find()
                ->where(['user_id' => $userId])
                ->all();
            $livestocks = Livestock::find()
                ->where(['user_id' => $userId])
                ->all();
            
            return $this->render('index', [
                'sapi' => $livestockCount,
                'cage' => $cageCount,
                'cages'=> $cages,
                'livestocks'=> $livestocks,
            ]);
        }
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
    public function actionProfile()
    {
        return $this->render('profile');
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
    
    // public function actionCreateKandang(){
    //     $cage = new Cage();
    //     $cage->user_id = 2;
    //     //$cage->user_id = Yii::$app->user->id;
    //     if ($cage->load(Yii::$app->request->post()) && $cage->save()) {
    //         Yii::$app->response->statusCode = 201;
    //         return $this -> render('create-kandang',[
    //             'message' => 'Kandang berhasil dibuat',
    //             'error' => false,
    //             'data' => $cage,
    //         ]);
            
    //     }
    //     // else {
    //     //     Yii::$app->response->statusCode = 400;
    //     //     return [
    //     //         'message' => 'Gagal membuat kandang', 
    //     //         'error' => true, 
    //     //         'details' => $this->getValidationErrors($cage),
    //     //     ];}
    //     return $this->render('create-kandang', [
    //         'cage' => $cage,
    //     ]);

    // }
    
    public function actionRegister(){
        return $this->render('register');
    }
    
    public function actionBcs(){
        $bcs = new BodyCountScore();
        $bcs->livestock_id = 2;
        //$bcs->user_id = Yii::$app->user->id;
        if ($bcs->load(Yii::$app->request->post()) && $bcs->save()) {
            Yii::$app->response->statusCode = 201;
            return $this -> render('bcs',[
                'message' => 'Kandang berhasil dibuat',
                'error' => false,
                'data' => $bcs,
            ]);
            
        }
        else {
            Yii::$app->response->statusCode = 400;
            return $this -> render ('error',[
                'message' => 'Gagal membuat kandang', 
                'error' => true, 
                'details' => $this->getValidationErrors($bcs),
            ]);}
        }
}
