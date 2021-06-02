<?php

namespace frontend\assets;

/**
 * Class CategoryAsset
 * @package frontend\assets
 */
class CategoryAsset extends \yii\web\AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';

    public $css = [
        'css/category.css'
    ];

    public $js = [
    ];

    public $depends = [
        AppAsset::class
    ];
}