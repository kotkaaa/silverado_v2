<?php

namespace common\modules\events;

use yii\base\Event;

/**
 * Interface EventListenerInterface
 * @package common\modules\EventDispatcher
 */
interface EventListenerInterface
{
    /**
     * @param Event $event
     */
    public function execute(Event $event);
}