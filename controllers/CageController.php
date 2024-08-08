<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\auth\HttpBearerAuth;
use app\models\Cage;
use app\models\User;
use app\controllers\BaseController;
use app\controllers\SiteController;
use yii\filters\VerbFilter;

class CageController extends SiteController
{
    public $modelClass = 'app\models\Cage';

    /**
     * @inheritdoc
     */
    
     public function actions()
    {
        $actions = parent::actions();

        // Disable default CRUD actions
        unset($actions['index'], $actions['view'], $actions['create'], $actions['update'], $actions['delete']);

        return $actions;
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        // Menambahkan authenticator untuk otentikasi menggunakan access token
        // $behaviors['authenticator'] = [
        //     'class' => HttpBearerAuth::class,
        //     'except' => ['options'],
        // ];

        // Menambahkan VerbFilter untuk memastikan setiap action hanya menerima HTTP method yang sesuai
        $behaviors['verbs'] = [
            'class' => VerbFilter::class,
            'actions' => [
                'create' => ['POST'],
                'update' => ['PUT'],
                'delete' => ['DELETE'],
                'view' => ['GET'],
                'get-cages' => ['GET'],
            ],
        ];
        

        return $behaviors;
    }

    public function beforeAction($action)
    {
        if (!parent::beforeAction($action)) {
            return false;
        }

        // Since all actions require a token, directly verify the token without checking the action's id
        // $token = Yii::$app->request->getHeaders()->get('Authorization');
        // if ($token !== null && !User::verifyJwt($token)) {
        //     throw new \yii\web\UnauthorizedHttpException('Your token is invalid or expired.');
        //     return false;
        // }

        return true; // Proceed with the action since the token is valid
    }

    /**
     * Menampilkan data Cage.
     * @return mixed
     */
    public function actionIndex()
    {
        $model = new Cage();
        $userId = Yii::$app->user->identity->id;
        $error = [];

        // Get the list of cages based on user_id
        $cage = Cage::find()
            ->where(['user_id' => $userId])
            ->all();
        return $this-> render('index',[
            'cage' => $cage,
            'model' => $model,
            'error' => $error,
        ]);
    }

    /**
     * Menampilkan data Cage berdasarkan ID.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $cage = Cage::findOne($id);

        if ($cage) {
            Yii::$app->response->statusCode = 200;
            return [
                'message' => 'Data kandang berhasil ditemukan',
                'error' => false,
                'data' => $cage,
            ];
        } else {
            Yii::$app->response->statusCode = 404;
            return [
                'message' => "Kandang tidak ditemukan",
                'error' => true,
            ];
        }
    }

    /**
     * Mendapatkan daftar nama kandang berdasarkan pengguna yang sedang login.
     * @return array
     */
    public function actionGetCages()
    {
        // Get the ID of the currently logged in user
        $userId = Yii::$app->user->identity->id;

        // Get the list of cages based on user_id
        $cages = Cage::find()
            ->where(['user_id' => $userId])
            ->all();

        if (!empty($cages)) {
            // If the query was successful, return the list of cages, a success message, and an error status of false
            Yii::$app->response->statusCode = 200;
            return [
                'message' => 'Berhasil mendapatkan seluruh daftar kandang.',
                'error' => false,
                'data' => $cages,
            ];
        } else {
            // If the query failed, return an error message, an error status of true, and no cages found message
            Yii::$app->response->statusCode = 404;
            return [
                'message' => 'Tidak ada kandang yang ditemukan',
                'error' => true,
            ];
        }
    }

    /**
     * Membuat data Cage baru.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Cage();
        // $model->scenario = Cage::SCENARIO_CREATE;
        $model->user_id = Yii::$app->user->id;
        $params = Yii::$app->request->post('Cage', []);
        $model->load(['Cage' => $params]);
        if (!$model->validate()) {
            // Debug validation errors
            var_dump($model->getErrors());
            return $this->render('index', [
                'model' => $model,
            ]); 
        }else{
            $model->save();
            return $this->redirect(['index']);
        }
    }
    
    /**
     * Mengupdate data Cage berdasarkan ID.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        // Find the cage by ID
        $model = Cage::findOne($id);

        // Check if the cage exists
        if (!$model) {
            Yii::$app->session->setFlash('error', 'Kandang tidak ditemukan.');
            return $this->redirect(['index']);
        }

        // If the form is submitted
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            // Save the changes
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Kandang berhasil diperbarui.');
                return $this->redirect(['index']);
            } else {
                Yii::$app->session->setFlash('error', 'Gagal memperbarui data.');
            }
        }

        // Render the update view with the model data
        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Menghapus data Cage berdasarkan ID.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $cage = Cage::findOne($id);
        if ($cage->delete()) {
                Yii::$app->session->setFlash('success', 'Kandang berhasil dihapus.');
                return $this->redirect(['cage/index']);
            }

        // if (!ctype_digit($id)) {
        //     Yii::$app->session->setFlash('error', 'ID tidak valid.');
        //     return $this->redirect(['cage/index']);
        // }
    
    
        // if ($cage) {
        //     if (!empty($cage->livestocks)) {
        //         Yii::$app->session->setFlash('error', 'Kandang tidak dapat dihapus karena masih memiliki hewan ternak di dalamnya.');
        //         return $this->redirect(['cage/index']);
        //     }    
        //     if ($cage->delete()) {
        //         Yii::$app->session->setFlash('success', 'Kandang berhasil dihapus.');
        //         return $this->redirect(['cage/index']);
        //     }
        // }
    
        // Yii::$app->response->statusCode = 404;
        // return [
        //     'message' => 'Gagal menghapus kandang, kandang tidak ditemukan.',
        //     'error' => true,
        // ];
    }
}
