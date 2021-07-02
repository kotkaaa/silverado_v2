<?php

use yii\helpers\Html;
use kartik\rating\StarRating;

/** @var \common\models\Review $model */
?>

<div class="product-review__item">
    <div class="author">
        <?= Html::encode($model->author) ?><br>
        <small><?= \Yii::$app->formatter->asDate($model->created_at) ?></small>
    </div>

    <div class="comment">
        <?= Html::encode($model->comment) ?>
    </div>

    <div class="rate">
        <?= StarRating::widget([
            'name' => 'rate',
            'value' => $model->rate,
            'pluginOptions' => [
                'readonly' => true,
                'showClear' => false,
                'showCaption' => false,
                'stars' => 5,
                'step' => 1,
                'min' => 0,
                'max' => 5,
                'size' => 'm',
                'theme' => 'krajee-uni'
            ]
        ]) ?>
    </div>
</div>
