<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Category */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Categories', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="category-view">

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
            'parent_uuid',
            'title',
            'description:raw',
            'alias',
            [
                'attribute' => 'icon',
                'format' => 'raw',
                'value' => function () use ($model) {
                    if ($model->icon) {
                        return Html::img(\Yii::$app->params['frontUrl'] . '/uploads/category/' . $model->icon, ['class' => 'img-thumbnail']);
                    }

                    return null;
                }
            ],
            [
                'attribute' => 'image',
                'format' => 'raw',
                'value' => function () use ($model) {
                    if ($model->image) {
                        return Html::img(\Yii::$app->params['frontUrl'] . '/uploads/category/' . $model->image, ['class' => 'img-thumbnail']);
                    }

                    return null;
                }
            ],
            'meta_title',
            'meta_description:ntext',
            'meta_keywords:ntext',
            'meta_robots',
            'created_at',
            'updated_at',
            'position',
            'active:boolean',
            'separator:boolean',
        ],
    ]) ?>

</div>
