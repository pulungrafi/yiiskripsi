<?php 
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

/**
 * @var yii\web\View $this 
 * @var yii\widgets\ActiveForm $form 
 * @var app\models\Cage $model 
 */

 $this->title = 'Daftar Kandang';
 ?>

<div class="page-content"> 
    <section class="row">
        <div class="col-12 col-lg-7">
            <section class="section">
                <div class="card">
                    <div class="card-content">
                        <div class="card-body cage-form">
                            <h4 class="card-title mb-4">Buat Kandang Baru</h4>
                                <?php $form = ActiveForm::begin([
                                    'id' => 'cage-form',
                                    'action' => 'cage/create',
                                    'method' => 'POST',
                                ]); ?>
                                <div class="form-body">
                                    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
                                    <?= $form->field($model, 'location')->textInput(['maxlength' => true]) ?>
                                    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>
                                </div>
                                <div class="form-actions d-flex justify-content-end mt-3">
                                            <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>

                                    <!-- <button type="submit" class="btn btn-primary me-1">Submit</button> -->
                                </div>
                                <?php ActiveForm::end(); ?>
                        </div>
                    </div>
                </div>
            </section>
        </div>

        <div class="col-12 col-lg-5">
            <div class="card">
                <div class="card-header">
                    <h4>Kandang Terdaftar</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-borderless mb-0">
                        <?php if (!empty($cage)): ?>
                                        <thead>
                                            <tr>
                                                <th>Nama Kandang</th>
                                                <th>Lokasi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($cage as $cage): ?>
                                            <tr>
                                                <td class="text-bold-500 post"><?= $cage->name ?></td>
                                                <td><?= $cage->location ?></td>
                                                <td><div class="comment-actions">
                                                    <button class="btn icon icon-left btn-primary me-2 text-nowrap" data-bs-toggle="modal" data-bs-target="#modalView<?= $cage->id ?>">
                                                        <i class="bi bi-eye-fill"></i> Show
                                                    </button>
                                                    <?= Html::a(
                                                        '<i class="bi bi-pencil-square"></i> Edit',
                                                        ['cage/update', 'id' => $cage->id],
                                                        [
                                                            'class' => 'btn icon icon-left btn-warning me-2 text-nowrap',
                                                            'data' => [
                                                                'method' => 'put', // Use GET method to access the update page
                                                            ],
                                                        ]
                                                    ) ?>                                                    
                                                    <?= Html::a(
                                                        '<i class="bi bi-x-circle"></i> Remove',
                                                        ['cage/delete', 'id' => $cage->id],
                                                        [
                                                            'class' => 'btn icon icon-left btn-danger me-2 text-nowrap',
                                                            'data' => [
                                                                'confirm' => 'Apakah Anda yakin ingin menghapus kandang ini?',
                                                                'method' => 'delete',
                                                            ],
                                                        ]
                                                    ) ?>
                                                </div></td>
                                            </tr>
                                            <div class="modal fade" id="modalView<?= $cage->id ?>" tabindex="-1" aria-labelledby="modalViewLabel<?= $cage->id ?>" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="modalViewLabel<?= $cage->id ?>">Detail Kandang</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <strong>Nama Kandang:</strong> <?= $cage->name ?><br>
                                                            <strong>Lokasi:</strong> <?= $cage->location ?><br>
                                                            <strong>Deskripsi:</strong> <?= $cage->description ?>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php endforeach; ?>
                                        </tbody>
                                    <?php else: ?>
                                        <p>Anda belum memiliki kandang.</p>
                                    <?php endif; ?>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
