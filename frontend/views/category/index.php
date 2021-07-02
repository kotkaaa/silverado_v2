<?php

use yii\helpers\Html;
use yii\widgets\Pjax;
use yii\widgets\ListView;
use yii\widgets\Breadcrumbs;
use frontend\assets\CategoryAsset;

/** @var \yii\web\View $this **/
/** @var \common\models\Category $model */
/** @var \yii\data\DataProviderInterface $dataProvider */
/** @var \common\builders\CatalogSearchModel $searchModel */
/** @var \common\models\Filter[] $filters */

$this->title = $model->title;

$this->params['breadcrumbs'][] = [
    'label' => $this->title
];

CategoryAsset::register($this);
?>

<h1 class="heading-title"><?= $model->title ?></h1>

<?= Breadcrumbs::widget(['links' => $this->params['breadcrumbs']]) ?>

<?php Pjax::begin(['timeout' => 30000, 'options' => ['class' => 'category-row']]) ?>
    <div class="col-left">
        <?= $this->render('_filters', [
            'filters' => $filters
        ]) ?>
    </div>

    <div class="col-right">

        <?= ListView::widget([
            'dataProvider' => $dataProvider,
            'itemView' => '_product-item',
            'layout' => '<div class="toolbar">' . $this->render('_sort', ['searchModel' => $searchModel]) . ' {summary}</div> <div class="product-grid">{items}</div> {pager}',
            'pager' => [
                'pagination' => [
                    'pageSize' => 1,
                    'forcePageParam' => false,
                    'pageSizeParam' => false
                ]
            ]
        ]) ?>

        <div class="seo-text"><?= Html::decode($model->description) ?></div>
    </div>
<?php Pjax::end() ?>
