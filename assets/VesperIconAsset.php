<?php

namespace app\assets;

use yii\web\AssetBundle;

class VesperIconsAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';

    public $css = [
        'vesper-icons/style.css', // Sesuaikan dengan lokasi CSS Vesper
    ];

    public $js = [
        'vesper-icons/scripts.js', // Jika ada file JS, tambahkan di sini
    ];

    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
