<?php


namespace frontend\assets;

/**
 * Class HomeAsset
 * @package frontend\assets
 */
class HomeAsset extends \yii\web\AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';

    public $css = [
        'css/home.css',
    ];

    public $js = [
        'js/home.js',
    ];

    public $depends = [
        AppAsset::class,
        SwiperAsset::class
    ];
}