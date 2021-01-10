<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\Order;
use common\models\OrderInfo;

/* @var $this yii\web\View */
/* @var $model common\models\Order */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Orders', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="order-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
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
            'id',
            'created_at',
            'updated_at',
            'deleted_at',
            [
                'attribute' => 'status',
                'value' => Order::statusList()[$model->status]
            ],
            [
                'attribute' => 'source',
                'value' => Order::sourceList()[$model->source]
            ],
        ],
    ]) ?>

    <h3>Order Details</h3>

    <?= DetailView::widget([
        'model' => $model->orderInfo,
        'attributes' => [
            'user_name',
            'user_phone',
            [
                'attribute' => 'user_email',
                'format' => 'email'
            ],
            'comment',
            [
                'attribute' => 'payment_type',
                'value' => OrderInfo::paymentList()[$model->orderInfo->payment_type]
            ],
            [
                'attribute' => 'delivery_type',
                'value' => OrderInfo::deliveryList()[$model->orderInfo->delivery_type]
            ],
            'location',
            'address',
            'note'
        ],
    ]) ?>

    <h3>Products</h3>

    <?= \yii\grid\GridView::widget([
        'dataProvider' => new \yii\data\ArrayDataProvider([
            'allModels' => $model->orderProducts
        ]),
        'columns' => [
            'title',
            'sku',
            'quantity',
            'price:currency',
            [
                'label' => 'Subtotal',
                'value' => function (\common\models\OrderProduct $model) {
                    return $model->quantity ? $model->price * $model->quantity : $model->price;
                },
                'format' => [
                    'currency',
                    'UAH'
                ]
            ]
        ]
    ]) ?>
</div>
