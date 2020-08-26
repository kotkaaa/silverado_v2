<?php

use yii\helpers\Html;
use yii\grid\GridView;
use \common\models\Filter;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\FilterSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Filters';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="filter-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Filter', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'title',
            [
                'attribute' => 'strategy',
                'filter' => Filter::strategyList(true)
            ],
            [
                'attribute' => 'attribute_title',
                'label' => 'Attribute',
                'value' => 'attributeModel.title'
            ],
            [
                'attribute' => 'option_title',
                'label' => 'Option',
                'value' => 'optionModel.title'
            ],
            'position',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
