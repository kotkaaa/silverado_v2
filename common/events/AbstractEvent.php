<?php

namespace common\events;

use yii\base\Configurable;
use yii\base\Event;

/**
 * Class AbstractEvent
 * @package common\events
 */
class AbstractEvent extends Event
{
    /** @var Configurable */
    public $item;

    /**
     * AbstractEvent constructor.
     * @param $item
     * @param array $config
     */
    public function __construct($item, $config = [])
    {
        $this->item = $item;
        parent::__construct($config);
    }
}