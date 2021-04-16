<?php

/** @var \common\classes\Request\CallbackRequest $request */
?>

Замовлення зворотнього дзвінка від <?= $request->name ?>.
Зателефонуйте за номером <?= $request->phone ?> до <?= (new \DateTime())->setTimestamp($request->expires_at)->format('H:i') ?>, <?= (new \DateTime())->setTimestamp($request->expires_at)->format('d.m.Y') ?>
