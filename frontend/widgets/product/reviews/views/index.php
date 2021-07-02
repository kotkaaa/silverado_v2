<?php

use yii\widgets\Pjax;
use yii\widgets\ListView;

/** @var \common\models\Review $model */
/** @var \yii\data\DataProviderInterface $dataProvider */
/** @var \common\models\Product $product */
/** @var int $average */
/** @var \yii\web\View $this */
?>

<div class="product-review">
    <h3>
        Відгуки (<?= $dataProvider->getTotalCount() ?>)

        <span class="average">
            <i class="glyphicon glyphicon-star<?php if (!$average):?>-empty<?php endif;?>"></i>
            <small><?= $average ?? 'Відсутній' ?></small>
        </span>
    </h3>

    <?= $this->render('_form', [
        'model' => $model
    ]) ?>

    <?php Pjax::begin(['enablePushState' => false]) ?>
        <?= ListView::widget([
            'dataProvider' => $dataProvider,
            'emptyText' => $this->render('_empty'),
            'layout' => '{items}{pager}',
            'itemView' => '_item',
            'itemOptions' => [
                'tag' => false,
            ],
            'options' => [
                'class' => 'product-review__list'
            ]
        ]) ?>
    <?php Pjax::end() ?>
</div>
