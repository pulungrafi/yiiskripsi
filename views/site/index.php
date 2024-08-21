<?php
use yii\helpers\Html;
use yii\helpers\HtmlPurifier;
use yii\helpers\Url;
use yidas\yii\fontawesome\FontawesomeAsset;



/**
 * @var \yii\web\View $this
 * @var app\models\Livestock $livestock
 */
$this->title = 'Dashboard';
FontawesomeAsset::register($this);
?>
<link rel="stylesheet" href="/assets/scss/iconly.scss"> 
<div class="page-content"> 
    <section class="row">
        <div class="col-12 col-lg-12">
            <div class="row">
                <div class="col-6 col-lg-6 col-md-6">
                    <div class="card">
                        <div class="card-body px-4 py-4-5">
                            <div class="row">
                                <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-end ">
                                    <div class="stats-icon purple mb-2">
                                        <i class="fa-solid fa-house-flag"></i>
                                        <!-- <i class="bi-house-fill "></i> -->
                                    </div>
                                </div>
                                <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                    <h6 class="text-muted font-semibold">Kandang Terdaftar</h6>
                                    <h6 class="font-extrabold mb-0"><?= Html::decode($cage)?></h6>
                                </div>

                            </div> 
                        </div>
                    </div>
                </div>
                <div class="col-6 col-lg-6 col-md-6">
                    <div class="card"> 
                        <div class="card-body px-4 py-4-5">
                            <div class="row">
                                <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-end ">
                                    <div class="stats-icon blue mb-2">
                                        <i class="fa-solid fa-cow"></i>
                                    </div>
                                </div>
                                <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                    <h6 class="text-muted font-semibold">Sapi Terdaftar</h6>
                                    <h6 class="font-extrabold mb-0"><?= Html::encode("{$sapi}") ?></h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h4>Kandang Saya</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-borderless mb-0">
                                    <?php if (!empty($cages)): ?>
                                        <thead>
                                            <tr>
                                                <th>Nama Kandang</th>
                                                <th>Lokasi</th>
                                                <th>Kapasitas</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($cages as $cage): ?>
                                            <tr>
                                                <td class="text-bold-500 post"><?= $cage->name ?></td>
                                                <td><?= $cage->location ?></td>
                                                <td><?= $cage->getLivestockCount() . '/' . $cage->capacity ?></td>
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
                <div class="col-12 col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h4 href="<?= Url::toRoute(['/cage/index']) ?>">Sapi Saya</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-borderless mb-0">
                                <?php if (!empty($livestocks)): ?> 
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
                            <?php foreach ($livestocks as $livestock): ?>
                                <tr>
                                    <td class="text-bold-500"><?= $livestock->vid?></td>
                                    <td class="text-bold-500"><?= $livestock->name ?></td>
                                    <td class="text-bold-500"><?= $livestock->cage->name ?></td>
                                    <td class="text-bold-500"><?= $livestock->age ?> tahun</td>
                                    <td class="text-bold-500"><?= $livestock->health ?></td>
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
            </div>
            <!-- <div class="row">
                <div class="col-12 col-xl-4">
                    <div class="card">
                        <div class="card-header">
                            <h4>Profile Visit</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-7">
                                    <div class="d-flex align-items-center">
                                        <svg class="bi text-primary" width="32" height="32" fill="blue"
                                            style="width:10px">
                                            <use
                                                xlink:href="assets/static/images/bootstrap-icons.svg#circle-fill" />
                                        </svg>
                                        <h5 class="mb-0 ms-3">Europe</h5>
                                    </div>
                                </div>
                                <div class="col-5">
                                    <h5 class="mb-0 text-end">862</h5>
                                </div>
                                <div class="col-12">
                                    <div id="chart-europe"></div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-7">
                                    <div class="d-flex align-items-center">
                                        <svg class="bi text-success" width="32" height="32" fill="blue"
                                            style="width:10px">
                                            <use
                                                xlink:href="assets/static/images/bootstrap-icons.svg#circle-fill" />
                                        </svg>
                                        <h5 class="mb-0 ms-3">America</h5>
                                    </div>
                                </div>
                                <div class="col-5">
                                    <h5 class="mb-0 text-end">375</h5>
                                </div>
                                <div class="col-12">
                                    <div id="chart-america"></div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-7">
                                    <div class="d-flex align-items-center">
                                        <svg class="bi text-success" width="32" height="32" fill="blue"
                                            style="width:10px">
                                            <use
                                                xlink:href="assets/static/images/bootstrap-icons.svg#circle-fill" />
                                        </svg>
                                        <h5 class="mb-0 ms-3">India</h5>
                                    </div>
                                </div>
                                <div class="col-5">
                                    <h5 class="mb-0 text-end">625</h5>
                                </div>
                                <div class="col-12">
                                    <div id="chart-india"></div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-7">
                                    <div class="d-flex align-items-center">
                                        <svg class="bi text-danger" width="32" height="32" fill="blue"
                                            style="width:10px">
                                            <use
                                                xlink:href="assets/static/images/bootstrap-icons.svg#circle-fill" />
                                        </svg>
                                        <h5 class="mb-0 ms-3">Indonesia</h5>
                                    </div>
                                </div>
                                <div class="col-5">
                                    <h5 class="mb-0 text-end">1025</h5>
                                </div>
                                <div class="col-12">
                                    <div id="chart-indonesia"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-xl-8">
                    <div class="card">
                        <div class="card-header">
                            <h4>Latest Comments</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover table-lg">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Comment</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="col-3">
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar avatar-md">
                                                        <img src="./assets/compiled/jpg/5.jpg">
                                                    </div>
                                                    <p class="font-bold ms-3 mb-0">Si Cantik</p>
                                                </div>
                                            </td>
                                            <td class="col-auto">
                                                <p class=" mb-0">Congratulations on your graduation!</p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="col-3">
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar avatar-md">
                                                        <img src="./assets/compiled/jpg/2.jpg">
                                                    </div>
                                                    <p class="font-bold ms-3 mb-0">Si Ganteng</p>
                                                </div>
                                            </td>
                                            <td class="col-auto">
                                                <p class=" mb-0">Wow amazing design! Can you make another tutorial for
                                                    this design?</p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="col-3">
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar avatar-md">
                                                        <img src="./assets/compiled/jpg/8.jpg">
                                                    </div>
                                                    <p class="font-bold ms-3 mb-0">Singh Eknoor</p>
                                                </div>
                                            </td>
                                            <td class="col-auto">
                                                <p class=" mb-0">What a stunning design! You are so talented and creative!</p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="col-3">
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar avatar-md">
                                                        <img src="./assets/compiled/jpg/3.jpg">
                                                    </div>
                                                    <p class="font-bold ms-3 mb-0">Rani Jhadav</p>
                                                </div>
                                            </td>
                                            <td class="col-auto">
                                                <p class=" mb-0">I love your design! It’s so beautiful and unique! How did you learn to do this?</p>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div> -->
        </div>
    </section>
</div>