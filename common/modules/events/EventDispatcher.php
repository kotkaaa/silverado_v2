<?php

namespace common\modules\events;

use yii\base\BootstrapInterface;
use yii\base\Component;
use yii\base\Event;

/**
 * Class EventDispatcher
 * @package common\modules\user
 */
class EventDispatcher implements BootstrapInterface
{

    /** @var array */
    public $listeners = [];

    /**
     * @param \yii\base\Application $app
     */
    public function bootstrap($app)
    {
        foreach ($this->listeners as $listener => $class) {
            Event::on($class, '*', function ($event) use ($listener) {

                /** @var EventListenerInterface $class */
                $class = \Yii::$container->get($listener);

                if (!($class instanceof EventListenerInterface)) {
                    throw new InvalidListenerException();
                }

                $class->execute($event);
            });
        }
    }
}