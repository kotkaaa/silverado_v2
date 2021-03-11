<?php


namespace frontend\assets;

/**
 * Class SwiperAsset
 * @package frontend\assets
 */
class SwiperAsset extends \yii\web\AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';

    public $js = [
        'lib/swiper/swiper-bundle.js'
    ];

    public $css = [
        'lib/swiper/swiper-bundle.css',
        'css/swiper-overrides.css'
    ];

    public $depends = [
        AppAsset::class
    ];
}