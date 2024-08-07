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
                    <img src="<?= "{$themeMazer->baseUrl}/images/logo/logo.svg" ?>" alt="Logo" />
                </a>
            </div>
            <h1 class="auth-title">Log in.</h1>
            <p class="auth-subtitle mb-5">Log in with your data that you entered during registration.</p>

            <?php if (Yii::$app->session->hasFlash('success')): ?>
                <div class="alert alert-success">
                    <?= Yii::$app->session->getFlash('success') ?>
                </div>
            <?php endif; ?>

            <?php if (Yii::$app->session->hasFlash('error')): ?>
                <div class="alert alert-danger">
                    <?= Yii::$app->session->getFlash('error') ?>
                </div>
            <?php endif; ?>
            
            <form action="index.html">
                <?php $form = ActiveForm::begin([
                    'id' => 'login-form',
                    'action' => ['user/login'],
                    'method'=> 'POST',
                    'enableClientValidation'=>true,
                ]); ?>
                <div class="form-group position-relative mb-4">
                    <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="form-group position-relative mb-4">
                    <?= $form->field($model, 'password')->passwordInput(['maxlength' => true]) ?>
                </div>
                <?= Html::submitButton('Submit', ['class' => 'btn btn-primary']) ?>
                <?php ActiveForm::end(); ?>
            </form>
            <div class="text-center mt-5 text-lg fs-4">
                <p class="text-gray-600">
                    Don't have an account?
                    <a href="<?= Url::toRoute(['signup']) ?>" class="font-bold">
                        SignUp
                    </a>.
                </p>
                <p>
                    <a class="font-bold" href="<?= Url::toRoute(['forgot-password']) ?>">
                        Forgot password
                    </a>
                </p>
            </div>
        </div>
    </div>
</div>