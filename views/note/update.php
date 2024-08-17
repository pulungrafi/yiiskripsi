<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use app\models\Livestock;

/* @var $this yii\web\View */
/* @var $model app\models\Note */
/* @var $form yii\widgets\ActiveForm */

$this->title = 'Update Catatan: ' . $model->id;
?>

<div class="cage-update">

        <?php $form = ActiveForm::begin([
            'id' => 'update-form',
            'method' => 'put',
        ]); ?>
<div class="form-body">
                                        <div class="form-body row">
                                            <div class = "col">
                                            <?= $form->field($model, 'livestock_id')->dropDownList(
                                        \yii\helpers\ArrayHelper::map(Livestock::find()->where(['user_id' => Yii::$app->user->id])->all(), 'id', 'name'),
                                        ['prompt' => 'Pilih Sapi']
                                    ) ?>
                                    <?= $form->field($model, 'livestock_feed')->textInput(['maxlength' => true]) ?>
                                    <?= $form->field($model, 'costs')->textInput(['type' => 'number', 'min' => 0]) ?>
                                    <?= $form->field($model, 'feed_weight')->textInput(['type' => 'number', 'min' => 0]) ?>
                                    <?= $form->field($model, 'vitamin')->textInput(['maxlength' => true]) ?>
                                    <?= $form->field($model, 'details')->textarea(['rows' => 6]) ?>

        <div class="form-group">
            <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
            <button type="submit" href= '<?= Url::toRoute(['note/index']) ?>' class="btn btn-primary me-1">Cancel</button>
        </div>


        <?php ActiveForm::end(); ?>
</div>
