<?php

use yii\helpers\Html;
use yii\widgets\ListView;

/** @var \yii\web\View $this **/
/** @var \common\models\Category $model */
/** @var \yii\data\DataProviderInterface $dataProvider */
/** @var \common\models\Filter[] $filters */

$this->title = $model->meta_title;
?>

<h1><?= $model->title ?></h1>

<div class="row">

    <div class="col-sm-4">
        <?= $this->render('_filters', [
            'filters' => $filters
        ]) ?>
    </div>

    <div class="col-sm-8">
        <?= ListView::widget([
            'dataProvider' => $dataProvider,
            'itemView' => '_product-item',
            'summary' => '',
            'pager' => [
                'pagination' => [
                    'pageSize' => 1,
                    'forcePageParam' => false,
                    'pageSizeParam' => false
                ]
            ]
        ]) ?>
    </div>
</div>

<hr>

<?= Html::decode($model->description) ?>
