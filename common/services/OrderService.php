<?php


namespace common\services;

/**
 * Class OrderService
 * @package common\services
 */
class OrderService extends \yii\base\Component
{
    /** @var string */
    public const EVENT_AFTER_CREATE = 'afterCreate';
    public const EVENT_AFTER_CONFIRM = 'afterConfirm';
    public const EVENT_AFTER_CANCEL = 'afterCancel';
    public const EVENT_AFTER_COMPLETE = 'afterComplete';
}