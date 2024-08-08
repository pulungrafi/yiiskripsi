<?php

use yii2\theme\mazer\MainAsset;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var string $content
 */

$themeMazer = MainAsset::register($this);
?>


<div class="row h-100">
    <div class="col-lg-5 col-12">
        <div id="auth-left">
            <div class="auth-logo">
                <a href="<?= Url::current() ?>">
                    <img src="<?= "{$themeMazer->baseUrl}/images/logo/logo.svg" ?>" alt="Logo" style="width: 200px;" />
                </a>
            </div>
            <h1 class="auth-title">Sign Up</h1>
            <p class="auth-subtitle mb-5">Input your data to register to our website.</p>

            <!-- <form method="GET"> -->
            <?php $form = ActiveForm::begin([
                    'id' => 'login-form',
                    'action' => ['user/register'],
                    'method'=> 'post'               
                    ]); ?>
                <div class="form-group position-relative mb-4">
                    <!-- <input type="text" class="form-control form-control-xl" placeholder="Email"> -->
                    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>
                    <!-- <div class="form-control-icon">
                        <i class="bi bi-envelope"></i>
                    </div> -->
                </div>
                <div class="form-group position-relative mb-4">
                    <!-- <input type="text" class="form-control form-control-xl" placeholder="Username"> -->
                    <!-- <div class="form-control-icon">
                        <i class="bi bi-person"></i>
                    </div> -->
                    <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="form-group position-relative mb-4">
                    <!-- <input type="password" class="form-control form-control-xl" placeholder="Password">
                    <div class="form-control-icon">
                        <i class="bi bi-shield-lock"></i>
                    </div> -->
                    <?= $form->field($model, 'password')->passwordInput(['maxlength' => true]) ?>
                </div>
                <?= Html::submitButton('Regis', ['class' => 'btn btn-primary']) ?>
                <?php ActiveForm::end(); ?>
                <!-- <button class="btn btn-primary btn-block btn-lg shadow-lg mt-5">Sign Up</button> -->
            <!-- </form> -->
            <div class="text-center mt-5 text-lg fs-4">
                <p class='text-gray-600'>Already have an account? <a href="index" class="font-bold">Log
                        in</a>.</p>
            </div>
        </div>
    </div>
    <div class="col-lg-7 d-none d-lg-block">
        <div id="auth-right">

        </div>
    </div>
</div>