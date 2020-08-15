<?php

namespace common\modules\File\storages;

use common\modules\File\behaviours\FileBehaviour;
use common\modules\File\helpers\FileHelper;
use common\modules\File\storages\interfaces\StorageInterface;
use yii\base\BaseObject;
use yii\base\Model;
use yii\web\UploadedFile;

/**
 * Class AbstractStorage
 * @package common\modules\File\storages
 */
abstract class AbstractStorage extends BaseObject implements StorageInterface
{

    /** @var FileBehaviour */
    protected $behavior;

    /** @var \Closure */
    public $nameGeneratorFunction;

    /**
     * AbstractStorage constructor.
     * @param FileBehaviour $behavior
     * @param array $config
     */
    public function __construct(FileBehaviour $behavior, $config = [])
    {
        $this->behavior = $behavior;

        $this->nameGeneratorFunction = $config['nameGeneratorFunction'] ?? function (Model $model, UploadedFile $file) {
                return FileHelper::getStoreFileName($model, $file);
            };

        parent::__construct($config);
    }

    /**
     * @return array
     */
    public function __sleep()
    {
        return [];
    }
}