<?php

use app\models\Livestock;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use app\models\BodyCountScore;

/**
 * @var yii\web\View $this 
 * @var yii\widgets\ActiveForm $form 
 * @var app\models\BodyCountScore $model 
 */

 $this->title = 'Tambah BCS';
 ?>

<div class="page-content"> 
    <section class="row">
        <div class="col-12 col-lg-5">
            <section class="section">
                <div class="card">
                    <div class="card-content">
                        <div class="card-body bcs-form">
                            <h4 class="card-title mb-4">Buat BCS Baru</h4>
                                <?php $form = ActiveForm::begin([
                                    'id' => 'bcs-form',
                                    'action' => 'create',
                                    'method' => 'POST',
                                ]); ?>
                                <div class="form-body">
                                    <?= $form->field($model, 'livestock_id')->dropDownList(
                                        \yii\helpers\ArrayHelper::map(Livestock::find()->where(['user_id' => Yii::$app->user->id])->all(), 'id', 'name'),
                                        ['prompt' => 'Pilih Sapi']
                                    )->label('Nama Ternak') ?>
                                    <?= $form->field($model, 'body_weight')->input('number', ['placeholder' => 'Masukkan berat badan (kg)']) ?>
                                    <?= $form->field($model, 'chest_size')->input('number', ['placeholder' => 'Masukkan ukuran dada (cm)']) ?>
                                    <?= $form->field($model, 'hips')->input('number', ['placeholder' => 'Masukkan ukuran pinggul (cm)']) ?>
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

        <div class="col-12 col-lg-7">
            <div class="card">
                <div class="card-header">
                    <h4>Log BCS</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-borderless mb-0">
                        <?php if (!empty($bcs)): ?>
                                        <thead>
                                            <tr>
                                                <th>Nama Sapi</th>
                                                <th>Berat Sapi</th>
                                                <th>Lingkar Dada</th>
                                                <th>Ukuran Pinggul</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($bcs as $bcs): ?>
                                                <tr>
                                                    <td class="text-bold-500 post"><?= $bcs->livestock->name ?></td>
                                                    <td><?= $bcs->body_weight ?></td>
                                                    <td><?= $bcs->chest_size ?></td>
                                                    <td><?= $bcs->hips ?></td>  
                                                    <td><div class="comment-actions">
                                                        <button class="btn icon icon-left btn-primary me-2 text-nowrap" data-bs-toggle="modal" data-bs-target="#modalView<?= $bcs->livestock_id ?>">
                                                            <i class="bi bi-eye-fill"></i> Show
                                                        </button>
                                                        <?= Html::a(
                                                            '<i class="bi bi-pencil-square"></i> Edit',
                                                            ['bcs/update', 'id' => $bcs->id],
                                                            [
                                                                'class' => 'btn icon icon-left btn-warning me-2 text-nowrap',
                                                                'data' => [
                                                                    'method' => 'put',
                                                                    ]
                                                            ]
                                                        ) ?>
                                                        <?= Html::a(
                                                            '<i class="bi bi-x-circle"></i> Remove',
                                                            ['bcs/delete', 'id' => $bcs->id],
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

                                                <div class="modal fade" id="modalView<?= $bcs->livestock_id ?>" tabindex="-1" aria-labelledby="modalViewLabel<?= $bcs->livestock_id ?>" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="modalViewLabel<?= $bcs->livestock_id ?>">Riwayat Pencatatan BCS - <?= $bcs->livestock->name ?></h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <?php 
                                                                $history = BodyCountScore::find()->where(['livestock_id' => $bcs->livestock_id])->orderBy(['created_at' => SORT_DESC])->all();
                                                                foreach ($history as $record): ?>
                                                                    <strong><?= $record->created_at ?>:</strong><br>
                                                                    Berat: <?= $record->body_weight ?><br>
                                                                    Lingkar Dada: <?= $record->chest_size ?><br>
                                                                    Ukuran Pinggul: <?= $record->hips ?><br><br>
                                                                <?php endforeach; ?>
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
                                        <p>Anda belum memiliki log BCS.</p>
                                    <?php endif; ?>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
