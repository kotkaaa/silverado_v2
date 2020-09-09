<?php

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    /** @var string */
    public $basePath = '@webroot';
    public $baseUrl = '@web';

    /** @var array */
    public $css = [
        'css/site.css',
    ];

    /** @var array */
    public $js = [
        'js/tools/file_upload.js'
    ];

    /** @var array */
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'yii\jui\JuiAsset',
    ];
}
