<?php

namespace common\modules\File\storages;

use common\modules\File\storages\interfaces\AfterValidateStorageInterface;
use yii\base\InvalidConfigException;
use yii\web\UploadedFile;

/**
 * Class FieldStorage
 * @package common\modules\File\storages
 */
class FieldStorage extends AbstractStorage implements AfterValidateStorageInterface
{
    /** @var string */
    public $field;

    /**
     * @throws InvalidConfigException
     */
    public function init()
    {
        if (!$this->field) {
            throw new InvalidConfigException('Field is required.');
        }

        parent::init();
    }

    /**
     * @param UploadedFile[] $files
     * @return mixed|void
     */
    public function process(array $files)
    {

        if ($this->behavior->multiple) {
            $names = [];
            foreach ($files as $file) {
                $names[] = ($this->nameGeneratorFunction)($this->behavior->owner, $file);
            }
            $this->behavior->owner->{$this->field} = $names;
        } else {
            $this->behavior->owner->{$this->field} = ($this->nameGeneratorFunction)($this->behavior->owner, $files[0]);
        }
    }
}