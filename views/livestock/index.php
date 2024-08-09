<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use app\models\Cage;
/**
 * @var \yii\web\View $this
 * @var yii\widgets\ActiveForm $form
 * @var app\models\Livestock $model
 * @var app\models\LivestockImage $image
 */

$this->title = 'Daftar Sapi';
?>
<div class="page-content"> 
    <section class="row">
        <div class="col-12">
            <section class="section">
                <div class="card">
                    <div class="card-content">
                        <div class="card-body">
                            <h4 class="card-title mb-4">Buat Sapi Baru</h4>
                                <?php $form = ActiveForm::begin([
                                    'id'=> 'livestock-form',
                                    'action'=> 'create',
                                    'method'=> 'post',
                                ]); ?>
                                    <div class="form-body">
                                        <div class="form-body row">
                                            <div class = "col">
                                            <?= $form->field($model, 'eid')->textInput(['maxlength' => true, 'placeholder' => 'Masukkan kode EID']) ?>
                                            <?= $form->field($model, 'name')->textInput(['maxlength' => true, 'placeholder' => 'Masukkan nama hewan ternak']) ?>
                                            <?= $form->field($model, 'birthdate')->input('date', ['placeholder' => 'Masukkan tanggal lahir']) ?>
                                            <?= $form->field($model, 'cage_id')->dropDownList(
                                                \yii\helpers\ArrayHelper::map(Cage::find()->where(['user_id' => Yii::$app->user->id])->all(), 'id', 'name'),
                                                ['prompt' => 'Pilih Kandang']
                                            ) ?>

                                            <?= $form->field($model, 'type_of_livestock')->dropDownList([
                                                'Kambing' => 'Kambing',
                                                'Sapi' => 'Sapi',
                                            ], ['prompt' => 'Pilih jenis hewan ternak']) ?>

                                            <?= $form->field($model, 'breed_of_livestock')->dropDownList([
                                                'Madura' => 'Madura',
                                                'Bali' => 'Bali',
                                                'Limousin' => 'Limousin',
                                                'Brahman' => 'Brahman',
                                            ], ['prompt' => 'Pilih ras hewan ternak']) ?>

                                            <?= $form->field($model, 'purpose')->dropDownList([
                                                'Indukan' => 'Indukan',
                                                'Penggemukan' => 'Penggemukan',
                                                'Tabungan' => 'Tabungan',
                                                'Belum Tahu' => 'Belum Tahu',
                                            ], ['prompt' => 'Pilih tujuan pemeliharaan']) ?>

                                            <?= $form->field($model, 'maintenance')->dropDownList([
                                                'Kandang' => 'Kandang',
                                                'Gembala' => 'Gembala',
                                                'Campuran' => 'Campuran',
                                            ], ['prompt' => 'Pilih jenis pemeliharaan']) ?>

                                            </div>
                                            <div class = "col">
                                                <?= $form->field($model, 'vid')->textInput(['maxlength' => true, 'placeholder' => 'Masukkan kode VID']) ?>
                                                <?= $form->field($model, 'ownership_status')->dropDownList([
                                                    'Sendiri' => 'Sendiri',
                                                    'Kelompok' => 'Kelompok',
                                                    'Titipan' => 'Titipan',
                                                ], ['prompt' => 'Pilih status kepemilikan']) ?>

                                            <?= $form->field($model, 'reproduction')->dropDownList([
                                                'Tidak Bunting' => 'Tidak Bunting',
                                                'Bunting < 1 bulan' => 'Bunting < 1 bulan',
                                                'Bunting 1 bulan' => 'Bunting 1 bulan',
                                                'Bunting 2 bulan' => 'Bunting 2 bulan',
                                                'Bunting 3 bulan' => 'Bunting 3 bulan',
                                                'Bunting 4 bulan' => 'Bunting 4 bulan',
                                                'Bunting 5 bulan' => 'Bunting 5 bulan',
                                                'Bunting 6 bulan' => 'Bunting  bulan',
                                                'Bunting 7 bulan' => 'Bunting 7 bulan',
                                                'Bunting 8 bulan' => 'Bunting 8 bulan',
                                                'Bunting 9 bulan' => 'Bunting 9 bulan',
                                                'Bunting 10 bulan' => 'Bunting 10 bulan',
                                                'Bunting 11 bulan' => 'Bunting 11 bulan',
                                                'Bunting > 11 bulan' => 'Bunting > 11 bulan',
                                            ], ['prompt' => 'Pilih status reproduksi']) ?>

                                            <?= $form->field($model, 'gender')->dropDownList([
                                                'Jantan' => 'Jantan',
                                                'Betina' => 'Betina',
                                            ], ['prompt' => 'Pilih jenis kelamin']) ?>

                                            <?= $form->field($model, 'chest_size')->input('number', ['placeholder' => 'Masukkan ukuran dada (cm)']) ?>

                                            <?= $form->field($model, 'body_weight')->input('number', ['placeholder' => 'Masukkan berat badan (kg)']) ?>

                                            <?= $form->field($model, 'health')->dropDownList([
                                                'Sehat' => 'Sehat',
                                                'Sakit' => 'Sakit',
                                            ], ['prompt' => 'Pilih status kesehatan']) ?>
                                            <?= $form->field($model, 'source')->dropDownList([
                                                'Sejak Lahir' => 'Sejak Lahir',
                                                'Bantuan Pemerintah' => 'Bantuan Pemerintah',
                                                'Beli' => 'Beli',
                                                'Beli dari Luar Kelompok' => 'Beli dari Luar Kelompok',
                                                'Beli dari Dalam Kelompok' => 'Beli dari Dalam Kelompok',
                                                'Inseminasi Buatan' => 'Inseminasi Buatan',
                                                'Kawin Alam' => 'Kawin Alam',
                                                'Tidak Tahu' => 'Tidak Tahu',
                                            ], ['prompt' => 'Pilih sumber hewan ternak']) ?>
                                            </div>
                                        </div>
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

        <div class="row">
            <div class="card">
                <div class="card-header">
                    <h4>Sapi Terdaftar</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-borderless mb-0">
                            <?php if (!empty($livestock)): ?> 
                            <thead>
                                <tr>
                                    <th>VID</th>
                                    <th>Nama</th>
                                    <th>Kandang</th>
                                    <th>Umur</th>
                                    <th>Kesehatan</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($livestock as $livestock): ?>
                                <tr>
                                    <td class="text-bold-500"><?= $livestock->vid?></td>
                                    <td class="text-bold-500"><?= $livestock->name ?></td>
                                    <td class="text-bold-500"><?= $livestock->cage->name ?></td>
                                    <td class="text-bold-500"><?= $livestock->age ?></td>
                                    <td class="text-bold-500"><?= $livestock->health ?></td>
                                    <td><div class="comment-actions">
                                                    <button class="btn icon icon-left btn-primary me-2 text-nowrap" data-bs-toggle="modal" data-bs-target="#modalView<?= $livestock->id ?>">
                                                        <i class="bi bi-eye-fill"></i> Show
                                                    </button>
                                                    <?= Html::a(
                                                        '<i class="bi bi-pencil-square"></i> Edit',
                                                        ['livestock/update', 'id' => $livestock->id],
                                                        [
                                                            'class' => 'btn icon icon-left btn-warning me-2 text-nowrap',
                                                            'data' => [
                                                                'method' => 'put', // Use GET method to access the update page
                                                            ],
                                                        ]
                                                    ) ?>                                                    
                                                    <?= Html::a(
                                                        '<i class="bi bi-x-circle"></i> Remove',
                                                        ['livestock/delete', 'id' => $livestock->id],
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
                                <div class="modal fade" id="modalView<?= $livestock->id ?>" tabindex="-1" aria-labelledby="modalViewLabel<?= $livestock->id ?>" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="modalViewLabel<?= $livestock->id ?>">Detail Sapi</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                        <?php foreach ($livestock->attributes as $attribute => $value): ?>
    <strong><?= ucfirst(str_replace('_', ' ', $attribute)) ?>:</strong> <?= $value ?><br>
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
                               <p>Anda belum memiliki Sapi.</p>
                            <?php endif; ?>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>