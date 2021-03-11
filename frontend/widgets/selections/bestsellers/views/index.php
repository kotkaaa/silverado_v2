<?php

use frontend\widgets\selections\bestsellers\assets\BestSellersAsset;

/** @var \yii\data\DataProviderInterface $dataProvider */
/** @var \yii\web\View $this */

BestSellersAsset::register($this);
?>

<div class="product__slider">
    <h2>Популярні моделі</h2>

    <div class="swiper-container product__slider-bestsellers">
        <div class="swiper-wrapper product-grid">
<?php foreach ($dataProvider->getModels() as $model): ?>
            <?= $this->render('@frontend/views/category/_product-item', [
                'model' => $model
            ]) ?>
<?php endforeach;?>
        </div>

        <div class="swiper-pagination bestsellers-swiper-pagination"></div>
    </div>
</div>
