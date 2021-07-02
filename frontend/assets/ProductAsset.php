<?php

namespace frontend\assets;

/**
 * Class ProductAsset
 * @package frontend\assets
 */
class ProductAsset extends \yii\web\AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';

    public $css = [
        'css/product.css'
    ];

    public $js = [
        'js/product.js'
    ];

    public $depends = [
        AppAsset::class,
        SwiperAsset::class
    ];
}