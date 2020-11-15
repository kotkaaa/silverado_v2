<?php

use yii\helpers\Html;

/** @var \common\models\Product $model */
?>

<div class="product-item">
    <?php if (preg_match('/^image+/i', $model->_preview->mime)): ?>
        <?= Html::img('/' . $model->_preview->url . '/middle-' . $model->_preview->name) ?>
    <?php else:?>
        <?= Html::tag('object', '', ['data' => '/' . $model->_preview->url . '/' . $model->_preview->name]) ?>
    <?php endif;?>
    <br>
    <p>
        <?= Html::a($model->title, '/product/' . $model->alias) ?>
    </p>
    <p>
        <?= \Yii::$app->formatter->asCurrency($model->price) ?>
    </p>
</div>
