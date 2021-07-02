<?php

use frontend\assets\ProductAsset;
use yii\widgets\Breadcrumbs;
use frontend\widgets\product\set\ProductSet;
use frontend\widgets\product\reviews\ProductReviews;

/** @var yii\web\View $this **/
/** @var \common\models\Product $model */

$this->title = $model->title;
$this->registerMetaTag(['name' => 'description', 'content' => $model->meta_description]);
$this->registerMetaTag(['name' => 'keywords', 'content' => $model->meta_keywords]);
$this->registerMetaTag(['name' => 'robots', 'content' => $model->meta_robots]);

$this->params['breadcrumbs'][] = [
    'label' => $model->category->title,
    'url' => '/category/' . $model->category->alias
];

$this->params['breadcrumbs'][] = [
    'label' => $model->title
];

ProductAsset::register($this);
?>

<h1 class="heading-title"><?= $model->title ?></h1>

<?= Breadcrumbs::widget(['links' => $this->params['breadcrumbs']]) ?>

<div class="product-card__wrap">
    <div class="product-card">
        <div class="product-card__row">
            <?= $this->render('_image', [
                'model' => $model
            ]) ?>

            <?= $this->render('_details', [
                'model' => $model
            ]) ?>
        </div>

        <?= ProductReviews::widget([
            'product' => $model
        ]) ?>
    </div>
</div>

<?= ProductSet::widget(['product' => $model]) ?>