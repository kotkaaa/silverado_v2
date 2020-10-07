<?php


namespace frontend\assets;


use yii\web\AssetBundle;

/**
 * Class CartAsset
 * @package frontend\assets
 */
class CartAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';

    public $css = [
    ];

    public $js = [
        'js/checkout.js',
        'lib/inputmask/dist/inputmask.min.js',
        'lib/inputmask/dist/jquery.inputmask.min.js',
        'lib/inputmask/dist/bindings/inputmask.binding.js',
    ];

    public $depends = [
        AppAsset::class
    ];
}