<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\Product;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ProductSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Products';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Product', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'tableOptions' => [
            'class' => 'table table-bordered'
        ],
        'rowOptions' => function(Product $model) {
            if (!$model->active) {
                return ['class' => 'danger'];
            }
        },
        'columns' => [
            [
                'attribute' => 'position',
                'label' => '#'
            ],
            [
                'label' => 'Preview',
                'format' => 'raw',
                'value' => function (Product $model) {
                    if (preg_match('/^image+/i', $model->_preview->mime)) {
                        return Html::img(\Yii::$app->params['frontUrl'] . '/' . $model->_preview->url . '/thumb-' . $model->_preview->name, ['class' => 'thumbnail']);
                    }

                    return Html::tag('object', '', ['data' => \Yii::$app->params['frontUrl'] . '/' . $model->_preview->url . '/' . $model->_preview->name, 'class' => 'thumbnail', 'width' => 90, 'height' => 90]);
                }
            ],
            'title',
            'sku',
            [
                'attribute' => 'category_title',
                'label' => 'Category',
                'value' => 'category.title'
            ],
            'price',
            'active:boolean',
            'created_at',
            'updated_at',
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{toggle} {view} {update} {delete}',
                'buttons' => [
                    'toggle' => function($url, Product $model) {
                        if (!$model->active) {
                            return Html::a('<span class="glyphicon glyphicon-play"></span>', ['toggle', 'id' => $model->uuid], [
                                'title' => 'Enable',
                            ]);
                        }

                        return Html::a('<span class="glyphicon glyphicon-pause"></span>', ['toggle', 'id' => $model->uuid], [
                            'title' => 'Disable',
                        ]);
                    }
                ]
            ],
        ],
    ]); ?>


</div>
