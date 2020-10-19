<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\Order;
use kartik\daterange\DateRangePicker;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\OrderSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Orders';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="order-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Order', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'id',
            [
                'attribute' => 'status',
                'filter' => Order::statusList(),
                'value' => function (Order $model) {
                    return Order::statusList()[$model->status];
                }
            ],
            [
                'attribute' => 'source',
                'filter' => Order::sourceList(),
                'value' => function (Order $model) {
                    return Order::sourceList()[$model->source];
                }
            ],
            [
                'label' => 'Created At',
                'format' => 'html',
                'attribute' => 'created_at',
                'value' => 'created_at',
                'filter' =>  DateRangePicker::widget([
                    'model' => $searchModel,
                    'attribute' => 'created_range',
                    'convertFormat' => true,
                    'pluginOptions' => [
                        'timePicker' => false,
                        'locale' => [
                            'format'=>'Y-m-d'
                        ]
                    ]
                ]),
            ],
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
