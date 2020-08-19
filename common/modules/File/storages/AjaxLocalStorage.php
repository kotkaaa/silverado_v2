<?php


namespace common\modules\File\storages;


use yii\base\InvalidConfigException;
use yii\web\UploadedFile;
use yii\imagine\Image;

/**
 * Class AjaxLocalStorage
 * @package common\modules\File\storages
 */
class AjaxLocalStorage extends LocalStorage
{
    /**
     * @param UploadedFile[] $files
     * @return mixed|void
     */
    public function process(array $files)
    {
        foreach ($files as $file) {

            if (!copy($this->behavior->tmpPath . DIRECTORY_SEPARATOR . $file->name, $this->path . DIRECTORY_SEPARATOR . $file->name)) {
                continue;
            }

            if ($this->thumbnails) {
                Image::thumbnail($this->path . '/' . $file->name, $this->thumbnailWidth, $this->thumbnailHeight)->save($this->path . '/' . $this->thumbnailPrefix . $file->name, ['quality' => $this->thumbnailQuality]);
            }
        }
    }
}