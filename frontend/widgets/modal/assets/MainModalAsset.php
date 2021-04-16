<?php


namespace frontend\widgets\modal\assets;

use yii\bootstrap\BootstrapAsset;

/**
 * Class MainModalAsset
 * @package frontend\widgets\modal\assets
 */
class MainModalAsset extends \yii\web\AssetBundle
{
    /** @var string */
    public $sourcePath = '@frontend/widgets/modal/resources';

    /** @var string[] */
    public $css = [
        'css/modal.css'
    ];

    /** @var string[] */
    public $js = [
        'js/modal.js'
    ];

    /** @var string[] */
    public $depends = [
        BootstrapAsset::class
    ];
}