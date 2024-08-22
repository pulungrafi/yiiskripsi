<?php

namespace app\controllers;

use Yii;
use yii\filters\auth\HttpBearerAuth;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;
use yii\web\Controller;
use yii\web\Response;
use app\models\Livestock;
use app\models\LivestockImage;
use app\models\Cage;
use app\models\Note;
use app\models\NoteImage;
use app\controllers\SiteController;
use app\models\BodyCountScore;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use yii\helpers\FileHelper;
use Google\Cloud\Storage\StorageClient;
use Google\Cloud\Storage\Acl;
use yii\data\Pagination;
use yii\helpers\Url;
use app\models\LoginForm;
use yii\data\ActiveDataProvider;




class LivestockController extends SiteController
{
    public $modelClass = 'app\models\Livestock';

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

        // VerbFilter untuk memastikan setiap action hanya menerima HTTP method yang sesuai
        $behaviors['verbs'] = [
            'class' => VerbFilter::class,
            'actions' => [
                'create' => ['POST'],
                'update' => ['PUT', 'PATCH'],
                'delete' => ['DELETE'],
                'view' => ['GET'],
                'search' => ['GET'],
                'get-livestocks' => ['GET'],
                'upload-image' => ['POST'],
                'bcs-data' => ['GET'],
            ],
        ];

        return $behaviors;
    }

    public function actionIndex()
{
    if (Yii::$app->user->isGuest) {
        $redirect = Url::to(['user/index']);
        $model = new LoginForm();
        return $this-> render($redirect , ['model'=> $model]);

     }
    else{
    $model = new Livestock();
    $userId = Yii::$app->user->identity->id;
    $cages = Cage::find()
        ->where(['user_id' => $userId])
        ->all();

    // Validasi cage_id berdasarkan user_id
    if (empty($cages)) {
        return $this->render('error', [
            'message' => 'Kandang tidak boleh kosong, mohon buat kandang terlebih dahulu.',
            'error' => true,
        ]);
    }

    // Load Livestock data
    $requestData = Yii::$app->getRequest()->getBodyParams();
    if ($model->load($requestData, '') && $model->save()) {
        $imageFiles = UploadedFile::getInstancesByName('livestock_image');
        $uploadedImages = [];

        if (!empty($imageFiles)) {
            foreach ($imageFiles as $index => $imageFile) {
                $imageName = Yii::$app->security->generateRandomString(12) . $index . '.' . $imageFile->getExtension();
                $uploadPath = Yii::getAlias('@webroot/uploads/livestock/' . $model->id . '/');
                
                // Create directory if it doesn't exist
                if (!is_dir($uploadPath)) {
                    mkdir($uploadPath, 0777, true);
                }

                // Save the image file
                $imageFile->saveAs($uploadPath . $imageName);

                // Save the image information to the database
                $livestockImage = new LivestockImage();
                $livestockImage->livestock_id = $model->id;
                $livestockImage->image_path = '/uploads/livestock/' . $model->id . '/' . $imageName;
                
                if ($livestockImage->save()) {
                    $uploadedImages[] = $livestockImage->image_path;
                }
            }
        }
    }

    // Pagination and fetching livestock data
    $query = Livestock::find()->where(['user_id' => $userId])->orderBy(['created_at' => SORT_DESC]);

    $pagination = new Pagination([
        'defaultPageSize' => 10,
        'totalCount' => $query->count(),
    ]);

    $livestock = $query->offset($pagination->offset)
                       ->limit($pagination->limit)
                       ->all();

    return $this->render('index', [
        'livestock' => $livestock,
        'model' => $model,
        'pagination' => $pagination,
    ]);
}}

    public function actionCreate(){
    $model = new Livestock();
    $model->user_id = Yii::$app->user->id;
    $params = Yii::$app->request->post('Livestock', []);
    $model->load(['Livestock' => $params]);
    if (!$model->validate()) {
        // Debug validation errors
        var_dump($model->errors);
        return $this->render('index', [
            'model' => $model,
        ]); 
    }else {
        // Simpan data Livestock
        if ($model->save()) {
            // Jika berhasil menyimpan data Livestock, simpan juga data BCS
            $bcs = new BodyCountScore();
            $bcs->livestock_id = $model->id;
            $bcs->chest_size = $model->chest_size;
            $bcs->hips = $model->hips;
            $bcs->body_weight = $model->body_weight;

            // Simpan data BCS
            if (!$bcs->save()) {
                // Jika gagal menyimpan BCS, tampilkan pesan error
                Yii::$app->session->setFlash('error', 'Gagal menyimpan data BCS.');
                return $this->render('index', [
                    'model' => $model,
                ]);
            }

            // Redirect ke halaman index jika semua berhasil
            return $this->redirect(['index']);
        } else {
            // Jika gagal menyimpan Livestock, tampilkan pesan error
            Yii::$app->session->setFlash('error', 'Gagal menyimpan data Livestock.');
            return $this->render('index', [
                'model' => $model,
            ]);
        }
    }}
  
    public function actionBcsData($id)
{
    $query = BodyCountScore::find()->where(['livestock_id' => $id]);
    $model = $this->findLivestockModel($id);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10, // Set page size as required
            ],
        ]);

        return $this->render('bcs', [
            'dataProvider' => $dataProvider,
            'model' => $model,
        ]);
}
protected function findLivestockModel($id)
    {
        if (($model = Livestock::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested livestock does not exist.');
    }

    
    protected function uploadImages($model, $imageFiles)
    {
        $userId = Yii::$app->user->identity->id;
        $uploadPath = Yii::getAlias('@webroot/livestock/' . $userId . '/' . $model->id . '/');
    
        // Membuat direktori jika belum ada
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0777, true);
        }
    
        $uploadedImages = [];
    
        foreach ($imageFiles as $index => $imageFile) {
            $imageName = Yii::$app->security->generateRandomString(12) . $index . '.' . $imageFile->getExtension();
            $filePath = $uploadPath . $imageName;
    
            // Menyimpan gambar ke direktori
            if ($imageFile->saveAs($filePath)) {
                // Simpan informasi gambar ke tabel livestock_images
                $livestockImage = new LivestockImage();
                $livestockImage->livestock_id = $model->id;
                $livestockImage->image_path = '/livestock/' . $userId . '/' . $model->id . '/' . $imageName;
                if (!$livestockImage->save()) {
                    Yii::$app->session->setFlash('error', 'Gagal menyimpan data gambar ke database.');
                } else {
                    $uploadedImages[] = $livestockImage->image_path;
                }
            } else {
                Yii::$app->session->setFlash('error', 'Gagal mengunggah gambar.');
            }
        }
    
        return $uploadedImages;
    }
    

    

    /**
     * Mengupdate data Livestock berdasarkan ID.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException jika data Livestock tidak ditemukan
     * @throws ServerErrorHttpException jika data Livestock tidak dapat diupdate
     */
    public function actionUpdate($id)
{
    $model = $this->findModel($id);

    if ($model->load(Yii::$app->request->post())) {

        $model->livestock_image = UploadedFile::getInstance($model, 'livestock_image');
        
        if ($model->save()) {
            if ($model->livestock_image) {
                if ($model->uploadImage()) {
                    return $this->redirect(['index']);
                } else {
                    return $this->redirect(['update','id'=> $model->id]);
                    // Kesalahan dalam mengunggah gambar
                }
            }

            return $this->redirect(['index']);
        }
    }

    return $this->render('update', [
        'model' => $model,
    ]);
}
    private function uploadImage()
{
    if ($this->validate()) {
        // Nama file unik untuk menghindari duplikasi
        $fileName = $this->id . '_' . uniqid() . '_' . $this->livestock_image->baseName . '.' . $this->livestock_image->extension;
        
        // Tentukan direktori penyimpanan
        $uploadDir = Yii::getAlias('@webroot/uploads');
        
        // Pastikan direktori ada, jika tidak maka buat direktori tersebut
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        
        // Gabungkan direktori dan nama file untuk membuat file path absolut
        $filePath = $uploadDir . '/' . $fileName;

        // Simpan file dan periksa apakah berhasil
        if ($this->livestock_image->saveAs($filePath)) {
            // Simpan konten gambar ke atribut 'image' di database
            $this->image = file_get_contents($filePath);
            return true;
        }
    }
    return false;
}




    /**
     * Menampilkan data Livestock berdasarkan ID.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException jika data Livestock tidak ditemukan
     */
    public function actionView($id)
    {
        $livestock = $this->findModel($id);

        if ($livestock) {
            Yii::$app->response->statusCode = 200;
            return $this-> render ('create-sapi', [
                'message' => 'Data ternak berhasil ditemukan.',
                'error' => false,
                'data' => $livestock,
            ]);
        } else {
            Yii::$app->response->statusCode = 404;
            return $this->render('create-sapi',[
                'message' => "Ternak tidak ditemukan",
                'error' => true,
            ]);
        }
    }

    /**
     * Deletes a Livestock model based on its primary key value.
     * If the deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
{
    $transaction = Yii::$app->db->beginTransaction();
    try {
        // Cari ternak berdasarkan ID
        $livestock = $this->findModel($id);

        // Jika ternak tidak ditemukan, tampilkan pesan error
        if ($livestock === null) {
            Yii::$app->session->setFlash('error', 'Gagal menghapus data ternak. Data ternak tidak ditemukan.');
            return $this->redirect(['index']);
        }

        // Dapatkan semua catatan yang terkait dengan ternak
        $notes = Note::find()->where(['livestock_id' => $id])->all();

        foreach ($notes as $note) {
            // Hapus gambar yang terkait dengan catatan terlebih dahulu
            NoteImage::deleteAll(['note_id' => $note->id]);

            // Hapus catatan
            $note->delete();
        }
        BodyCountScore::deleteAll(['livestock_id' => $id]);


        // Hapus gambar ternak
        LivestockImage::deleteAll(['livestock_id' => $id]);

        // Hapus ternak
        $livestock->delete();

        // Commit transaksi
        $transaction->commit();

        // Set flash message untuk sukses
        Yii::$app->session->setFlash('success', 'Data ternak berhasil dihapus.');
    } catch (\Exception $e) {
        // Rollback transaksi jika terjadi kesalahan
        $transaction->rollBack();
        Yii::$app->session->setFlash('error', 'Gagal menghapus data ternak. Alasan: ' . $e->getMessage());
    }

    // Redirect ke halaman index setelah operasi selesai
    return $this->redirect(['index']);
}



    /**
     * Mencari data Livestock berdasarkan VID.
     * @param string $vid
     * @return array
     */
    public function actionSearch($vid)
    {
        // Validasi pola VID
        if (!preg_match('/^[A-Z]{3}\d{4}$/', $vid)) {
            return [
                'message' => 'Format Visual ID tidak valid. Gunakan format tiga huruf kapital diikuti empat angka. Contoh: ABC1234.',
                'error' => true,
            ];
        }

        $userId = Yii::$app->user->identity->id;
        $livestock = Livestock::find()->where(['vid' => $vid, 'user_id' => $userId])->all();

        if ($livestock) {
            Yii::$app->getResponse()->setStatusCode(200); // OK
            return [
                'message' => 'Data ternak berhasil ditemukan.',
                'error' => false,
                'data' => $livestock,
            ];
        } else {
            Yii::$app->getResponse()->setStatusCode(404); // Not Found
            return [
                'message' => 'Data ternak tidak ditemukan.',
                'error' => true,
            ];
        }
    }

    /**
     * Retrieves livestock data by user_id.
     * @param integer $user_id
     * @return mixed
     */
    public function actionGetLivestocks($user_id)
    {
        $livestocks = Livestock::find()->where(['user_id' => $user_id])->all();

        if (!empty($livestocks)) {
            Yii::$app->getResponse()->setStatusCode(200); // OK
            return [
                'message' => 'Data ternak berhasil ditemukan.',
                'error' => false,
                'data' => $livestocks,
            ];
        } else {
            Yii::$app->getResponse()->setStatusCode(404); // Not Found
            return [
                'message' => 'Data ternak tidak ditemukan.',
                'error' => true,
            ];
        }
    }

    /**
     * Mengunggah gambar untuk Livestock berdasarkan ID.
     * @param integer $id
     * @return mixed
     * @throws ServerErrorHttpException jika gambar tidak dapat disimpan
     */
    // public function actionUploadImage($id)
    // {
    //     // Find the Livestock model based on ID
    //     $model = $this->findModel($id);

    //     // Get the image from the request
    //     $imageFiles = UploadedFile::getInstancesByName('livestock_image');

    //     if (!empty($imageFiles)) {
    //         // Get the user_id of the currently logged in user
    //         $userId = Yii::$app->user->identity->id;

    //         // Create a directory path based on user_id and Livestock id
    //         $uploadPath = 'livestock/' . $userId . '/' . $model->id . '/';

    //         $uploadedImages = [];

    //         // Initialize the Google Cloud Storage client
    //         $storage = new StorageClient([
    //             'keyFilePath' => Yii::getAlias('@app/config/sa.json')
    //         ]);
    //         $bucket = $storage->bucket('digiternak1');

    //         // Iterate through each uploaded file
    //         foreach ($imageFiles as $index => $imageFile) {
    //             // Check if the temporary file path is set
    //             if (empty($imageFile->tempName)) {
    //                 Yii::$app->response->statusCode = 400;
    //                 return [
    //                     'message' => 'Gagal mengunggah gambar. Silakan coba lagi.',
    //                     'error' => true,
    //                 ];
    //             }

    //             // Generate a unique file name
    //             $imageName = Yii::$app->security->generateRandomString(12) . $index . '.' . $imageFile->getExtension();
            
    //             // Save the file to the directory
    //             $object = $bucket->upload(
    //                 file_get_contents($imageFile->tempName),
    //                 ['name' => $uploadPath . $imageName]
    //             );

    //             // Make the object publicly accessible
    //             $object->update(['acl' => []], ['predefinedAcl' => 'publicRead']);
            
    //             // Get the public URL of the object
    //             $publicUrl = sprintf('https://storage.googleapis.com/%s/%s', $bucket->name(), $uploadPath . $imageName);

    //             // Save the image information to the livestock_images table
    //             $livestockImage = new LivestockImage();
    //             $livestockImage->livestock_id = $model->id;
    //             $livestockImage->image_path = $uploadPath . $imageName;
    //             if (!$livestockImage->save()) {
    //                 Yii::$app->response->statusCode = 400;
    //                 return [
    //                     'message' => 'Gagal menyimpan data gambar ke database.',
    //                     'error' => true,
    //                 ];
    //             }
            
    //             // Save the public URL to the array
    //             $uploadedImages[] = $publicUrl;
    //         }

    //         // If the model saving is successful
    //         Yii::$app->response->statusCode = 201;
    //         return [
    //             'message' => 'Gambar berhasil diunggah.',
    //             'error' => false,
    //             'data' => [
    //                 'livestock_images' => $uploadedImages,
    //             ],
    //         ];
    //     } else {
    //         Yii::$app->response->statusCode = 400;
    //         return [
    //             'message' => 'Tidak ada gambar yang diunggah.',
    //             'error' => true,
    //         ];
    //     }
    // }

    /**
     * Menemukan model Livestock berdasarkan ID.
     * @param integer $id
     * @return Livestock the loaded model
     * @throws NotFoundHttpException jika data Livestock tidak ditemukan
     */
    protected function findModel($id)
    {
        if (($model = Livestock::findOne($id)) !== null) {
            return $model;
        } else {
            return null;
        }
    }
}

// public function actionUploadImage($id)
    // {
    //     // Temukan model Livestock berdasarkan ID
    //     $model = $this->findModel($id);

    //     // Ambil gambar dari request
    //     $imageFiles = UploadedFile::getInstancesByName('livestock_image');

    //     if (!empty($imageFiles)) {
    //         // Ambil user_id dari pengguna yang sedang login
    //         $userId = Yii::$app->user->identity->id;

    //         // Buat path direktori berdasarkan user_id dan id Livestock
    //         $uploadPath = 'uploads/livestock/' . $userId . '/' . $model->id . '/';

    //         // Periksa apakah direktori sudah ada, jika tidak, buat direktori baru
    //         if (!is_dir($uploadPath)) {
    //             FileHelper::createDirectory($uploadPath);
    //         }

    //         $uploadedImages = [];

    //         // Iterasi melalui setiap file yang diunggah
    //         foreach ($imageFiles as $index => $imageFile) {
    //             // Generate nama file yang unik
    //             $imageName = Yii::$app->security->generateRandomString(12) . $index . '.' . $imageFile->getExtension();
            
    //             // Simpan file ke direktori
    //             $imageFile->saveAs($uploadPath . $imageName);
            
    //             // Save image information to the livestock_images table
    //             $livestockImage = new LivestockImage();
    //             $livestockImage->livestock_id = $model->id;
    //             $livestockImage->image_path = $uploadPath . $imageName;
    //             if (!$livestockImage->save()) {
    //                 Yii::$app->response->statusCode = 400;
    //                 return [
    //                     'message' => 'Gagal menyimpan data gambar ke database.',
    //                     'error' => true,
    //                 ];
    //             }
            
    //             // Simpan nama file ke dalam array
    //             $uploadedImages[] = $uploadPath . $imageName;
    //         }

    //         // Jika penyimpanan model berhasil
    //         Yii::$app->response->statusCode = 201;
    //         return [
    //             'message' => 'Gambar berhasil diunggah.',
    //             'error' => false,
    //             'data' => [
    //                 'livestock_images' => $uploadedImages,
    //             ],
    //         ];
    //     } else {
    //         Yii::$app->response->statusCode = 400;
    //         return [
    //             'message' => 'Tidak ada gambar yang diunggah.',
    //             'error' => true,
    //         ];
    //     }
    // }

    /**
     * Mengembalikan semua data Livestock.
     * @return array
     */
    // public function actionIndex()
    // {
    //     $livestocks = Livestock::find()->all();
        
    //     if (!empty($livestocks)) {
    //         Yii::$app->response->statusCode = 200;
    //         return $livestocks;
    //     } else {
    //         Yii::$app->getResponse()->setStatusCode(404); // Not Found
    //         return [
    //             'message' => 'Ternak tidak ditemukan.',
    //             'error' => true,
    //         ];
    //     }
    // }