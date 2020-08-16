<?php


namespace common\behaviors;

use common\models\Category;
use yii\base\Event;
use yii\base\InvalidConfigException;
use yii\db\ActiveRecord;
use yii\helpers\FileHelper;

/**
 * Class CategoryBehavior
 * @package common\behaviors
 *
 * @property Category $owner
 */
class CategoryBehavior extends \yii\base\Behavior
{
    /** @var string */
    public $uploadPath;

    /**
     * @throws InvalidConfigException
     */
    public function init()
    {
        if (!$this->uploadPath) {
            throw new InvalidConfigException('Upload path not set.');
        }

        if (!is_dir($this->uploadPath)) {
            throw new InvalidConfigException('Upload path not exist.');
        }

        parent::init();
    }

    /**
     * @return array
     */
    public function events(): array
    {
        return [
            ActiveRecord::EVENT_AFTER_DELETE => 'afterDelete'
        ];
    }

    /**
     * @param Event $event
     * @return void
     */
    public function afterDelete(Event $event): void
    {
        if ($this->owner->icon && is_file($this->uploadPath . DIRECTORY_SEPARATOR . $this->owner->icon)) {
            FileHelper::unlink($this->uploadPath . DIRECTORY_SEPARATOR . $this->owner->icon);
        }

        if ($this->owner->image && is_file($this->uploadPath . DIRECTORY_SEPARATOR . $this->owner->image)) {
            FileHelper::unlink($this->uploadPath . DIRECTORY_SEPARATOR . $this->owner->image);
        }
    }
}