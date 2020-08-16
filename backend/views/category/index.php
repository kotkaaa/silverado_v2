<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\grid\GridView;
use common\models\Category;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\CategorySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $parentModel \common\models\Category */

$this->title = 'Categories';

$this->params['breadcrumbs'][] = [
    'url' => Url::to(['/category/index']),
    'label' => $this->title
];

if ($parentModel->uuid) {
    $this->params['breadcrumbs'][] = [
        'url' => Url::to(['/category/index', Html::getInputName($searchModel, 'parent_uuid') => $parentModel->uuid]),
        'label' => $parentModel->title
    ];
}
?>
<div class="category-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Category', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'tableOptions' => [
            'class' => 'table table-bordered'
        ],
        'rowOptions' => function(Category $model) {
            if (!$model->active) {
                return ['class' => 'danger'];
            }
            if ($model->separator) {
                return ['class' => 'active'];
            }
        },
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'title',
            [
                'attribute' => 'has_children',
                'format' => 'raw',
                'label' => 'Children',
                'value' => function (Category $model) use ($searchModel) {
                    if ($model->children) {
                        return Html::a('<span class="glyphicon glyphicon-folder-open"></span>&nbsp;&nbsp;' . count($model->children), ['index', Html::getInputName($searchModel, 'parent_uuid') => $model->uuid]);
                    }

                    return null;
                }
            ],
            'active:boolean',
            'position',
            'created_at',
            'updated_at',

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{toggle} {view} {update} {delete}',
                'buttons' => [
                    'toggle' => function($url, Category $model) {
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
