<?php

/** @var \common\models\Order $order */
?>

Номер вашего заказа <?= $order->id ?>

ФИО: <?= $order->orderInfo->user_name ?>

Телефон: <?= $order->orderInfo->user_phone ?>

E-mail: <?= $order->orderInfo->user_email ?>

Город: <?= $order->orderInfo->location ?>

Адрес: <?= $order->orderInfo->address ?>

Комментарий к заказу: <?= $order->orderInfo->comment ?>