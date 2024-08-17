<?php

use yii2\theme\mazer\MainAsset;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var yii\widgets\ActiveForm $form
 * @var app\models\EditProfileForm $model
 */

$themeMazer = MainAsset::register($this);
$this->title = 'Profil Saya';

?>

<section class="section">
    <div class="row">
        <div class="col-12 col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="profile-form">

                    <?php $form = ActiveForm::begin([
                        'id' => 'profile-form',
                        'action' => ['user/profile'], // Mengirim ke actionProfile
                        'method' => 'post', // Gunakan metode POST
                    ]); ?>

                    <?= $form->field($model, 'username')->textInput() ?>
                    <?= $form->field($model, 'full_name')->textInput(['maxlength' => true]) ?>
                    <?= $form->field($model, 'gender')->dropDownList([
                        'Male' => 'Laki-laki',
                        'Female' => 'Perempuan',
                    ], ['prompt' => 'Pilih Gender']) ?>
                    <?= $form->field($model, 'birthdate')->input('date') ?>
                    <?= $form->field($model, 'phone_number')->textInput() ?>
                    <?= $form->field($model, 'address')->textarea(['rows' => 3]) ?>
                    <?= $form->field($model, 'nik')->textInput(['maxlength' => true]) ?>

                    <div class="form-group">
                        <?= Html::submitButton('Save', ['class' => 'btn btn-primary']) ?>
                    </div>

                    <?php ActiveForm::end(); ?>
                    
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
