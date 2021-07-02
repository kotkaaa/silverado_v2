<?php

/** @var \common\models\Product $master */
/** @var \common\models\Product[] $slaves */

$oldPrice = 0;
?>

<div class="product-set">
    <h3>Товари в комплекті</h3>

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
                <?= \Yii::$app->formatter->asCurrency($master->price) ?>
            </p>

<?php if ($oldPrice): ?>
            <p class="text-muted" style="text-decoration: line-through">
                <?= \Yii::$app->formatter->asCurrency($oldPrice) ?>
            </p>
<?php endif;?>
        </div>
    </div>
</div>
