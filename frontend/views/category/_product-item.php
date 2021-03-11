<?php

use yii\helpers\Html;

/** @var \common\models\Product $model */
?>

<div class="product-item swiper-slide">
    <div class="boundary">
        <div class="product-img">
            <a href="/product/<?= $model->alias ?>">
                <?= Html::img('/' . $model->_preview->url . '/middle-' . $model->_preview->name) ?>
            </a>
        </div>

        <p class="product-title">
            <?= Html::a($model->title, ['/product/' . $model->alias], ['data-pjax' => 0]) ?>
        </p>

        <p class="product-price">
            <a href="/product/<?= $model->alias ?>" class="add-to-cart">
                <span class="price">
                    <?= \Yii::$app->formatter->asCurrency($model->price) ?>
                </span>
            </a>

            <span class="price">
                <?= \Yii::$app->formatter->asCurrency($model->price) ?>
            </span>
        </p>
    </div>
</div>
