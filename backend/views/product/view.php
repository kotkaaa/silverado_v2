<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Product */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Products', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="product-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->uuid], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->uuid], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'uuid',
            'sku',
            'category_uuid',
            'title',
            'description:ntext',
            'short:ntext',
            'price',
            'discount',
            'viewed',
            'purchased',
            'rating',
            'position',
            'active:boolean',
            'alias',
            'meta_title',
            'meta_description:ntext',
            'meta_keywords:ntext',
            'meta_robots',
            'created_at',
            'updated_at',
            'deleted_at',
        ],
    ]) ?>

</div>
