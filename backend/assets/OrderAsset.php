<?php


namespace backend\assets;


use yii\web\AssetBundle;

/**
 * Class OrderAsset
 * @package backend\assets
 */
class OrderAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';

    public $css = [
    ];

    public $js = [
        'js/checkout.js',
        'lib/inputmask/dist/inputmask.min.js',
        'lib/inputmask/dist/jquery.inputmask.min.js',
        'lib/inputmask/dist/bindings/inputmask.binding.js'
    ];

    public $depends = [
        AppAsset::class
    ];
}