<?php

use yii\helpers\Html;
use yii\widgets\ListView;

/** @var \yii\web\View $this **/
/** @var \common\models\Category $model */
/** @var \yii\data\DataProviderInterface $dataProvider */
/** @var \common\models\Filter[] $filters */
/** @var string $query */

$this->title = $model->meta_title;
?>

<h1><?= $model->title ?></h1>

<div class="row">
    <div class="col-sm-4">
<?php foreach ($filters as $filter): ?>
    <?php if (!$filter->strategy->getCount()) continue;?>
    <h3><?= $filter->title ?></h3>
<?php foreach ($filter->strategy->getValues() as $value): ?>
    <a href="<?= $value->url ?>"><?= $value->label ?></a> (<?= $value->count ?>)<br>
<?php endforeach;?>
<?php endforeach;?>
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
                    'pageSizeParam' => false,
                    'params' => [
                        'category' => $model->alias
                    ]
                ]
            ]
        ]) ?>
    </div>
</div>

<hr>

<?= Html::decode($model->description) ?>
