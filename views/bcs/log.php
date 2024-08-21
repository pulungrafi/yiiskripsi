<?php

use app\models\Livestock;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use app\models\BodyCountScore;
use yii\widgets\LinkPager;

/**
 * @var yii\web\View $this 
 * @var yii\widgets\ActiveForm $form 
 * @var app\models\BodyCountScore $model 
 */

 $this->title = 'Riwayat BCS: ' . $model->livestock_id;
 
 ?>
 <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>Sapi Terdaftar</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="example" class="table table-borderless mb-0">
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
                                                     <button class="btn icon icon-left btn-info me-2" onclick="showBcsChart(<?= $livestock->id ?>)">                                                       
                                                         <i class="bi bi-file-bar-graph"></i>Grafik BCS
                                                        </button>

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
                                                        
                                                        <?php foreach ($livestock->attributes as $attributeLabels => $value): ?>
                                                            <?php foreach ($livestock->attributeLabels() as $keyattr => $valueattr):?>
                                                                <?php if ($keyattr === $attributeLabels ):?>
                                                                    <?php
                                                                        // Custom labels and formatting
                                                                        if ($keyattr === 'cage_id') {
                                                                            $value = $livestock->cage->name;
                                                                        }elseif ($keyattr === 'chest_size') {
                                                                            $label = ucfirst(str_replace('_', ' ', $valueattr));
                                                                            $value = $value . ' cm';
                                                                        } elseif ($keyattr === 'body_weight') {
                                                                            $label = ucfirst(str_replace('_', ' ', $valueattr));
                                                                            $value = $value . ' kg';
                                                                        } elseif ($keyattr === 'hips') {
                                                                            $label = ucfirst(str_replace('_', ' ', $valueattr));
                                                                            $value = $value . ' cm';
                                                                        } else {
                                                                            $label = ucfirst(str_replace('_', ' ', $valueattr));
                                                                        }
                                                                        ?>
                                                                    <strong><?= ucfirst(str_replace('_', ' ', $valueattr)) ?> : </strong> <?= $value ?><br>
                                                                <?php endif; ?>
                                                               
                                                                <?php endforeach; ?>
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
                            <?= LinkPager::widget([
                                'pagination' => $pagination,
                                'options' => ['class' => 'pagination pagination-primary mt-3'], // Set the pagination class
                                'linkOptions' => ['class' => 'page-link'], // Set the link class
                                'pageCssClass' => 'page-item', // Set the page item class
                                'prevPageCssClass' => 'page-item', // Set the previous button class
                                'nextPageCssClass' => 'page-item', // Set the next button class
                                'activePageCssClass' => 'active', // Set the active page class
                                'prevPageLabel' => '<i class="bi bi-chevron-left"></i>', // Previous page label
                                'nextPageLabel' => '<i class="bi bi-chevron-right"></i>', // Next page label
                                'disabledPageCssClass' => 'disabled', // Disabled page class
                            ]); ?>
                            <?php else: ?>
                               <p>Anda belum memiliki Sapi.</p>
                            <?php endif; ?>
                        </table>
                    </div>
                </div>
            </div>
        </div> 