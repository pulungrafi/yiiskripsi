<?php
use yii2\theme\mazer\MainAsset;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use app\models\Livestock;
use yii\helpers\StringHelper;

/**
 * @var \yii\web\View $this
 * @var \yii\widgets\ActiveForm $form
 * @var app\models\Note $model
 */

$this->title = 'Pencatatan Pakan Ternak';
?>
<div class="page-content"> 
    <div class ="row">
        <div class="col-12 col-lg-9">
            
            <section class="section">
                        <div class="card">
                            <div class="card-content">
                                <div class="card-body">
                                    <h4 class="card-title mb-4">Catatan Ternak</h4>
                                    <?php $form = ActiveForm::begin([
                                        'id' => 'note-form',
                                        'options' => ['enctype' => 'multipart/form-data'], // penting jika ada file upload
                                        'method' => 'post',
                                        'action' => 'create'
                                    ]); ?>
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
                                    </div>

                                    <?php ActiveForm::end(); ?>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
                <div class="col-12 col-lg-3">
    <div class="card">
        <div class="card-header">
            <h4>Catatan Terkini</h4>
            <div class="form-group position-relative mb-0 has-icon-left">
    <form action="<?= Url::to(['note/get-note-by-livestock-id']) ?>" method="get">
        <input type="text" name="livestock_id" class="form-control" placeholder="Cari catatan...">
        <div class="form-control-icon">
            <svg class="bi" width="1.5em" height="1.5em" fill="currentColor">
                <use xlink:href="assets/static/images/bootstrap-icons.svg#search" />
            </svg>
        </div>
        
    </form>
</div>

        </div>
        <div class="card-body">
            <div class="list-group">
                <?php if (!empty($notes)): ?>
                    <?php foreach ($notes as $note): ?>
                        <a href="#" class="list-group-item list-group-item-action" data-bs-toggle="modal" data-bs-target="#noteModal" data-id="<?= $note->id ?>">
                            <div class="d-flex w-100 justify-content-between">
                                <h5 class="mb-1"><?= StringHelper::truncate($note->livestock_name, 8, '...') ?></h5>
                                <small><?= Yii::$app->formatter->asRelativeTime($note->created_at) ?></small>
                            </div>
                            <p class="mb-1"><?= StringHelper::truncate($note->details, 80, '...') ?></p>
                            <small>Rp. <?= number_format($note->costs, 0, ',', '.') ?></small>
                        </a>
                        <?php endforeach; ?>
                        <div class="modal fade" id="noteModal" tabindex="-1" aria-labelledby="noteModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="noteModalLabel">Detail Catatan</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                    <?php foreach ($note->attributes as $attribute => $value): ?>
                                                                                    <strong><?= ucfirst(str_replace('_', ' ', $attribute)) ?>:</strong> <?= $value ?><br>
                                                                                <?php endforeach; ?>
                                    </div>
                                    <div class="modal-footer">
                                        <?= Html::a('Edit', ['note/update', 'id' => $note->id], ['class' => 'btn btn-primary', 'id' => 'editButton', 'data' => ['method' => 'put'],]) ?>
                                        <?= Html::a('Hapus', ['note/delete', 'id' => $note->id], ['class' => 'btn btn-danger', 'id' => 'deleteButton', 'data' => ['method' => 'delete'],]) ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                <?php else: ?>
                    <p>Tidak ada catatan tersedia.</p>
                <?php endif; ?>
                <!-- Modal -->

                
                <!-- <nav aria-label="Page navigation example">
                    <ul class="pagination pagination-primary mt-3">
                        <li class="page-item"><a class="page-link" href="#">
                                <span aria-hidden="true"><i class="bi bi-chevron-left"></i></span>
                            </a></li>
                        <li class="page-item active"><a class="page-link" href="#">1</a></li>
                        <li class="page-item"><a class="page-link" href="#">2</a></li>
                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                        <li class="page-item"><a class="page-link" href="#">
                                <span aria-hidden="true"><i class="bi bi-chevron-right"></i></span>
                            </a></li>
                    </ul>
                </nav> -->
            </div>
        </div>
    </div>
</div>

        
    </div> 
</div>
<!-- Bootstrap CSS -->
<link href="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">

<!-- Bootstrap JS (termasuk Popper.js) -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
