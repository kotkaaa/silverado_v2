<?php


namespace common\behaviors;

use common\models\Option;
use yii\base\Event;
use yii\db\ActiveRecord;

/**
 * Class OptionBehavior
 * @package common\behaviors
 *
 * @property Option $owner
 */
class OptionBehavior extends \yii\base\Behavior
{

    public function events()
    {
        return [
            ActiveRecord::EVENT_AFTER_FIND => 'afterFind'
        ];
    }

    /**
     * @param Event $event
     * @throws \yii\base\InvalidConfigException
     */
    public function afterFind(Event $event): void
    {
        if ($this->owner->strategy && class_exists($this->owner->strategy)) {
            $this->owner->strategy = \Yii::createObject([
                'class' => $this->owner->strategy,
                'model' => $this->owner
            ]);
        }
    }
}