<?php

namespace common\behaviors;

use common\models\Files;
use yii\base\Event;
use yii\db\ActiveRecord;
use yii\helpers\FileHelper;

/**
 * Class FilesBehavior
 * @package common\behaviors
 *
 * @property Files $owner
 */
class FilesBehavior extends \yii\base\Behavior
{
    /**
     * @var array
     * @example ['small', 'thumb']
     */
    public $thumbnails;

    /**
     * @return array
     */
    public function events()
    {
        return [
            ActiveRecord::EVENT_AFTER_DELETE => 'afterDelete',
            ActiveRecord::EVENT_INIT => 'afterInit',
            ActiveRecord::EVENT_AFTER_FIND => 'afterInit'
        ];
    }

    /**
     * @param Event $event
     * @throws \yii\base\InvalidConfigException
     */
    public function afterInit(Event $event): void
    {
        if (empty($this->owner->path . $this->owner->name)) {
            return;
        }

        if (!is_file($this->owner->path . DIRECTORY_SEPARATOR . $this->owner->name)) {
            return;
        }

        $this->owner->extension = pathinfo($this->owner->path . DIRECTORY_SEPARATOR . $this->owner->name, PATHINFO_EXTENSION);
        $this->owner->size = filesize($this->owner->path . DIRECTORY_SEPARATOR . $this->owner->name);
        $this->owner->mime = FileHelper::getMimeType($this->owner->path . DIRECTORY_SEPARATOR . $this->owner->name);
    }

    /**
     * @param Event $event
     */
    public function afterDelete(Event $event): void
    {
        if (is_file($this->owner->path . DIRECTORY_SEPARATOR . $this->owner->name)) {
            FileHelper::unlink($this->owner->path . DIRECTORY_SEPARATOR . $this->owner->name);
        }

        if (!$this->thumbnails) {
            return;
        }

        foreach ($this->thumbnails as $thumbnail) {
            if (is_file($this->owner->path . DIRECTORY_SEPARATOR . $thumbnail . '-' . $this->owner->name)) {
                FileHelper::unlink($this->owner->path . DIRECTORY_SEPARATOR . $thumbnail . '-' . $this->owner->name);
            }
        }
    }
}