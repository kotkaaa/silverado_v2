<?php

namespace common\modules\File\behaviours;

use common\modules\File\exception\InvalidFileStorageException;
use common\modules\File\storages\interfaces\AfterSaveStorageInterface;
use common\modules\File\storages\interfaces\AfterValidateStorageInterface;
use common\modules\File\storages\interfaces\StorageInterface;
use yii\base\Behavior;
use yii\db\ActiveRecord;
use yii\web\UploadedFile;

/**
 * Class FileBehaviour
 * @package common\modules\File\behaviours
 */
class FileBehaviour extends Behavior
{
    /** @var string */
    public $field;

    /** @var bool */
    public $multiple;

    /** @var */
    public $storages = [];

    /** @var array  */
    private $processedStorage = [];

    /**
     * @inheritdoc
     */
    public function events()
    {
        return [
            ActiveRecord::EVENT_BEFORE_VALIDATE => 'beforeValidate',
            ActiveRecord::EVENT_AFTER_VALIDATE => 'processFiles',
            ActiveRecord::EVENT_AFTER_INSERT => 'processFiles',
            ActiveRecord::EVENT_AFTER_UPDATE => 'processFiles',
        ];
    }

    /**
     * @throws InvalidFileStorageException
     */
    public function init()
    {
        foreach ($this->storages as $class => $config) {
            $storage = new $class($this, $config);
            if (!$storage instanceof StorageInterface) {
                throw new InvalidFileStorageException($class . ' is invalid type.');
            }
            $this->storages[$class] = $storage;
        }
        parent::init();
    }

    /**
     *
     */
    public function beforeValidate()
    {

        $files = $this->multiple ? UploadedFile::getInstances($this->owner, $this->field) : UploadedFile::getInstance($this->owner, $this->field);

        if (!$files) {
            return;
        }

        $this->owner->{$this->field} = $files;
    }


    /**
     * @param $event
     */
    public function processFiles($event)
    {

        if (!$this->owner->{$this->field}) {
            return;
        }

        $files = $this->multiple ? $this->owner->{$this->field} : [$this->owner->{$this->field}];

        foreach ($this->storages as $key => $storage) {

            $storageKey = $key . $this->field . 'file-storage';

            if (isset($this->processedStorage[$storageKey])) {
                continue;
            }

            /** @var StorageInterface $storage */
            if ($event->name === ActiveRecord::EVENT_AFTER_VALIDATE && $storage instanceof AfterValidateStorageInterface) {
                $this->processedStorage[$storageKey] = true;
                $storage->process($files);
            }

            if (($event->name === ActiveRecord::EVENT_AFTER_INSERT || $event->name === ActiveRecord::EVENT_AFTER_UPDATE) && $storage instanceof AfterSaveStorageInterface) {
                $this->processedStorage[$storageKey] = true;
                $storage->process($files);
            }
        }
    }

}