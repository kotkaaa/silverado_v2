<?php

namespace common\modules\File\assets;

use yii\web\AssetBundle;

/**
 * Class FileFieldAsset
 * @package common\modules\commerce\assets
 */
class FileFieldAsset extends AssetBundle
{
    public $basePath = '@common';
    public $sourcePath = '@common/modules/File/resources';

    public $js = [
        "js/file.field.js"
    ];

    public $depends = [
      //  \yii\web\JqueryAsset::class
    ];
}