<?php


namespace common\behaviors;

use common\models\Filter;
use yii\base\Event;
use yii\db\ActiveRecord;

/**
 * Class FilterBehavior
 * @package common\behaviors
 *
 * @property Filter $owner
 */
class FilterBehavior extends \yii\base\Behavior
{
    /**
     * @return array
     */
    public function events()
    {
        return [
            ActiveRecord::EVENT_AFTER_FIND => 'afterFind'
        ];
    }

    /**
     * @param Event $event
     */
    public function afterFind(Event $event): void
    {
        if (class_exists($this->owner->strategy_class)) {
            $this->owner->strategy = \Yii::createObject([
                'class' => $this->owner->strategy_class,
                'filter' => $this->owner
            ]);
        }
    }
}