<?php


namespace common\listeners;


use common\events\OrderEvent;
use common\modules\events\EventListenerInterface;
use common\services\OrderService;
use yii\base\Event;

/**
 * Class OrderListener
 * @package common\listeners
 */
class OrderListener implements EventListenerInterface
{
    /**
     * @param OrderEvent $event
     */
    public function execute(Event $event)
    {
        if (!$event instanceof OrderEvent) {
            return;
        }

        switch ($event->name) {
            // After confirm order
            case OrderService::EVENT_AFTER_CONFIRM:
                $template = 'order_confirm';
                $subject = 'Подтверждение заказа №%d';
                break;
            // After cancel order
            case OrderService::EVENT_AFTER_CANCEL:
                $template = 'order_cancel';
                $subject = 'Заказ №%d отменен';
                break;
            // After complete order
            case OrderService::EVENT_AFTER_COMPLETE:
                $template = 'order_complete';
                $subject = 'Заказ №%d выполнен';
                break;
            // Default : After create order
            default:
                $template = 'order_create';
                $subject = 'Новый заказ №%d';
                break;
        }

        \Yii::$app->mailer->compose("@frontend/views/mail/$template.php", ['order' => $event->item])
            ->setSubject(sprintf($subject, $event->item->id))
            ->setFrom([\Yii::$app->params['senderEmail'] => \Yii::$app->params['senderName']])
            ->setTo($event->item->orderInfo->user_email)
            ->send();

        \Yii::$app->sms->compose($template, ['order' => $event->item])
            ->setTo($event->item->orderInfo->user_phone)
            ->send();
    }
}