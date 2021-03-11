<?php


namespace frontend\widgets\selections\bestsellers\assets;

use frontend\assets\SwiperAsset;

/**
 * Class BestSellersAsset
 * @package frontend\widgets\selections\bestsellers
 */
class BestSellersAsset extends \yii\web\AssetBundle
{
    /** @var string */
    public $sourcePath = '@frontend/widgets/selections/bestsellers/resources';

    /** @var string[] */
    public $js = [
        'js/bestsellers.js'
    ];

    /** @var array */
    public $css = [];

    /** @var string[] */
    public $depends = [
        SwiperAsset::class
    ];
}