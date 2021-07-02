<?php

use yii\helpers\Html;

/** @var \common\models\Product $master */
/** @var \common\models\Product[] $slaves */

$oldPrice = 0;
?>

<div class="product-set">
    <h3>Разом дешевше!</h3>

    <div class="product-set__row">
        <div class="product-set__items">
<?php foreach ($slaves as $i => $slave): ?>
            <?= $this->render('@frontend/views/category/_product-item', [
                'model' => $slave
            ]) ?>

            <div class="divider">
                <?php if (++$i === count($slaves)): ?>=<?php else:?>+<?php endif;?>
            </div>

            <?php $oldPrice += $slave->price ?>
<?php endforeach;?>
        </div>

        <div class="product-set__summary">
            <p>Комплект разом:</p>

            <p class="price">
<?php if ($oldPrice): ?>
                <span style="text-decoration: line-through" class="text-muted"><?= $oldPrice ?></span>
<?php endif;?>
                <?= \Yii::$app->formatter->asCurrency($master->price) ?>
            </p>

            <p class="submit">
                <?= Html::a('Купити комплект', ['/product/' . $master->alias], ['class' => 'btn btn-default btn-lg']) ?>
            </p>
        </div>
    </div>
</div>

