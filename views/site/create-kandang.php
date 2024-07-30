<?php 
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this 
 * @var yii\widgets\ActiveForm $form 
 * @var app\models\Cage $cage 
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
                            <form class="form" method="post">
                                <?php $form = ActiveForm::begin(); ?>
                                <div class="form-body">
                                    <?= $form->field($cage, 'name')->textInput(['maxlength' => true]) ?>
                                    <?= $form->field($cage, 'location')->textInput(['maxlength' => true]) ?>
                                    <?= $form->field($cage, 'description')->textarea(['rows' => 6]) ?>
                                </div>
                                <div class="form-actions d-flex justify-content-end mt-3">
                                            <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>

                                    <!-- <button type="submit" class="btn btn-primary me-1">Submit</button> -->
                                </div>
                                <?php ActiveForm::end(); ?>
                            </form>
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
                        <?php if (!empty($cages)): ?>
                                        <thead>
                                            <tr>
                                                <th>Nama Kandang</th>
                                                <th>Lokasi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($cages as $cage): ?>
                                            <tr>
                                                <td class="text-bold-500 post"><?= $cage->name ?></td>
                                                <td><?= $cage->location ?></td>
                                                <!-- <td><div class="comment-actions">
                                                    <button class="btn icon icon-left btn-primary me-2 text-nowrap" data-bs-toggle="modal" data-bs-target="#border-less"><i class="bi bi-eye-fill"></i> Show</button>
                                                    <button class="btn icon icon-left btn-warning me-2 text-nowrap"><i class="bi bi-pencil-square"></i> Edit</button>
                                                    <button class="btn icon icon-left btn-danger me-2 text-nowrap"><i class="bi bi-x-circle"></i> Remove</button>
                                                </div></td> -->
                                            </tr>
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
