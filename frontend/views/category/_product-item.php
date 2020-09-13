<?php

use yii\helpers\Html;

/** @var \common\models\Product $model */
?>

<div class="product-item">
    <?= Html::img('/' . $model->_preview->url . '/middle-' . $model->_preview->name) ?><br>
    <p>
        <?= Html::a($model->title, '/product/' . $model->alias) ?>
    </p>
    <p>
        <?= \Yii::$app->formatter->asCurrency($model->price) ?>
    </p>
</div>
