<?php

namespace common\modules\File\storages;

use common\modules\File\storages\interfaces\AfterSaveStorageInterface;
use yii\base\InvalidCallException;
use yii\base\InvalidConfigException;
use yii\db\ActiveRecordInterface;

/**
 * Class RelationStorage
 * @package common\modules\File\storages
 */
class RelationStorage extends AbstractStorage implements AfterSaveStorageInterface
{

    public const STRATEGY_ADD = 'add';
    public const STRATEGY_UPDATE = 'update';

    /** @var string */
    public $strategy = self::STRATEGY_UPDATE;

    /** @var array */
    public $relations;

    /**
     * @throws InvalidConfigException
     */
    public function init()
    {

        if (!$this->relations) {
            throw new InvalidConfigException('Relations is required.');
        }

        foreach ($this->relations as $relation => $closure) {
            if (!$closure instanceof \Closure) {
                throw new InvalidConfigException($relation . ' is invalid closure type.');
            }
        }

        parent::init();
    }

    /**
     * @param array $files
     * @return mixed|void
     */
    public function process(array $files)
    {

        foreach ($this->relations as $relation => $closure) {

            if ($files && $this->strategy == self::STRATEGY_UPDATE) {
                $this->behavior->owner->unlinkAll($relation, true);
            }

            foreach ($files as $file) {

                $model = $closure($file);

                if (!$model instanceof ActiveRecordInterface) {
                    throw new InvalidCallException($relation . ' must return ActiveRecord interface.');
                }

                $model->save();
                $this->behavior->owner->link($relation, $model);
            }
        }
    }
}