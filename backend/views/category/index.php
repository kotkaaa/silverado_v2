<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\CategorySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Categories';
$this->params['breadcrumbs'][] = $this->title;
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
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'title',
            [
                'attribute' => 'parent_title',
                'label' => 'Parent',
                'value' => 'parent.title'
            ],
            [
                'attribute' => 'has_children',
                'format' => 'boolean',
                'label' => 'Children',
                'value' => function (\common\models\Category $model): ?string {
                    if ($model->children) {
                        return Html::a('<span class="glyphicon glyphicon-folder-open"></span>&nbsp;&nbsp;' . count($model->children), ['/support/admin', Html::getInputName($model, 'parent_uuid') => $model->uuid]);
                    }

                    return count($model->children) ? count($model->children) : null;
                }
            ],
            'active:boolean',
            'position',
            'created_at',
            'updated_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
