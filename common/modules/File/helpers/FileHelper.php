<?php

namespace common\modules\File\helpers;

use common\modules\File\storages\Preview;
use Intervention\Image\ImageManager;
use yii\base\Model;
use yii\db\ActiveRecordInterface;
use yii\helpers\FileHelper as FileHelperBase;
use yii\helpers\Inflector;
use yii\web\UploadedFile;

class FileHelper extends FileHelperBase
{

    public const QUALITY = 100;

    /**
     * @param Model $model
     * @param UploadedFile $file
     * @return string
     */
    public static function getStoreFileName(Model $model, UploadedFile $file): string
    {
        if ($model instanceof ActiveRecordInterface) {
            return $model->getPrimaryKey() . '_' . Inflector::slug($file->baseName) . '.' . $file->extension;
        }

        return Inflector::slug($file->baseName) . '.' . $file->extension;
    }

    /**
     * @param string $path
     * @param Preview $preview
     * @return bool|string
     */
    public static function createPreview(string $path, Preview $preview)
    {

        $filePath = dirname($path);
        $fileName = basename($path);
        $styleName = pathinfo($fileName, PATHINFO_FILENAME) . '_' . $preview->name . '.' . pathinfo($fileName, PATHINFO_EXTENSION);

        $imageManager = new ImageManager();

        if ($preview->algorithm == Preview::ALGORITHM_RESIZE) {
            $image = $imageManager->make($path)->resize($preview->width, $preview->height, function ($constraint) {
                $constraint->aspectRatio();
            });
        } else {
            $image = $imageManager->make($path)->fit($preview->width, $preview->height);
        }

        // $image = $imageManager->make($originPath . '/' . $file->name)->resize($style->width, $style->height);
        if ($image->save($filePath . '/' . $styleName, self::QUALITY)) {
            return $styleName;
        }
        return false;
    }

    /**
     * @param string $url
     * @param string $name
     * @return string|string[]
     */
    public static function getPreview(string $url, string $name)
    {

        $fileName = basename($url);
        $styleName = pathinfo($fileName, PATHINFO_FILENAME) . '_' . $name . '.' . pathinfo($fileName, PATHINFO_EXTENSION);

        return str_replace($fileName, $styleName, $url);
    }

}