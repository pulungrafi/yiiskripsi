<?php

use yii2\theme\mazer\MainAsset;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var yii\widgets\ActiveForm $form
 * @var app\models\User $model
 * @var app\models\LoginForm $model
 */

$themeMazer = MainAsset::register($this);
?>

<div class="row h-100">
    <div class="col-lg-5 col-12">
        <div id="auth-center">
            <div class="auth-logo">
                <a href="<?= Url::current() ?>">
                    <img src="<?= "{$themeMazer->baseUrl}/images/logo/logo.svg" ?>" alt="Logo" style="width: 200px;"/>
                </a>
            </div>
            <h1 class="auth-title">Log in.</h1>
            <p class="auth-subtitle mb-5">Log in with your data that you entered during registration.</p>
            


            <!-- <form> -->
                <?php $form = ActiveForm::begin([
                    'id' => 'login-form',
                    'action' => ['user/login'],
                    'method'=> 'POST'               
                    ]); ?>
                <div class="form-group position-relative mb-4">
                    <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="form-group position-relative mb-4">
                    <?= $form->field($model, 'password')->passwordInput(['maxlength' => true]) ?>
                </div>
                <?= Html::submitButton('login', ['class' => 'btn btn-primary']) ?>


                <?php ActiveForm::end(); ?>
            <!-- </form> -->
            <div class="text-center mt-5 text-lg fs-4">
                <p class="text-gray-600">
                    Don't have an account?
                    <a href="<?= Url::toRoute(['user/signup']) ?>" class="font-bold">
                        SignUp
                    </a>.
                </p>
            </div>
        </div>
    </div>
</div>