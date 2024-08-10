<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use app\models\Livestock;

/* @var $this yii\web\View */
/* @var $model app\models\Cage */
/* @var $form yii\widgets\ActiveForm */

$this->title = 'Update BCS: ' . $model->livestock_id;
?>

<div class="bcs-update">

    <div class="bcs-form">
        <?php $form = ActiveForm::begin([
            'id' => 'update-form',
            'method' => 'put',
        ]); ?>
            <?= $form->field($model, 'body_weight')->input('number', ['placeholder' => 'Masukkan berat badan (kg)']) ?>
            <?= $form->field($model, 'chest_size')->input('number', ['placeholder' => 'Masukkan ukuran dada (cm)']) ?>
            <?= $form->field($model, 'hips')->input('number', ['placeholder' => 'Masukkan ukuran pinggul (cm)']) ?>
        <div class="form-group">
            <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
            <button type="submit" href= '<?= Url::toRoute(['/bcs/index']) ?>' class="btn btn-primary me-1">Cancel</button>
        </div>


        <?php ActiveForm::end(); ?>
    </div>
</div>
