<?php

namespace common\modules\File\storages;

use common\modules\File\helpers\FileHelper;
use common\modules\File\storages\interfaces\AfterValidateStorageInterface;
use yii\base\InvalidConfigException;
use yii\web\UploadedFile;

/**
 * Class LocalStorage
 * @package common\modules\File\storages
 */
class LocalStorage extends AbstractStorage implements AfterValidateStorageInterface
{
    /** @var string */
    public $path;

    /** @var array */
    public $previews = [];

    /**
     * @throws InvalidConfigException
     */
    public function init()
    {
        if (!$this->path) {
            throw new InvalidConfigException('Path is required.');
        }

        parent::init();
    }

    /**
     * @param UploadedFile[] $files
     * @return mixed|void
     */
    public function process(array $files)
    {
        foreach ($files as $file) {

            $path = $this->path . '/' . ($this->nameGeneratorFunction)($this->behavior->owner, $file);

            $file->saveAs($path);

            /** @var Preview $preview */
            foreach ($this->previews as $preview) {
                FileHelper::createPreview($path, $preview);
            }

        }
    }
}