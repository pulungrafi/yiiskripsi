<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Cage */
/* @var $form yii\widgets\ActiveForm */

$this->title = 'Update Kandang: ' . $model->name;
?>

<div class="cage-update">

    <div class="cage-form">
        <?php $form = ActiveForm::begin([
            'id' => 'update-form',
            'method' => 'put',
        ]); ?>

        <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'location')->textInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'capacity')->input('number', ['placeholder' => 'Masukkan kapasitas kandang']) ?>


        <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?> 

        <div class="form-group">
            <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
            <button type="submit" href= '<?= Url::toRoute(['/cage/index']) ?>' class="btn btn-primary me-1">Cancel</button>
        </div>


        <?php ActiveForm::end(); ?>
    </div>
</div>
