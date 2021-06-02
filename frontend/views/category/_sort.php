<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\web\JsExpression;

/** @var \common\builders\CatalogSearchModel $searchModel */
?>

<?= Html::button('<span class="glyphicon glyphicon-filter"></span> <span class="text-muted">Фільтри</span>', [
    'class' => 'btn btn-link filters-toggle',
    'onclick' => new JsExpression('$(".filters").toggleClass("toggle-on");')
]) ?>

<span class="divider"></span>

<div class="dropdown sorter">
    <button id="sorting_dLabel" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="btn btn-link">
        <span class="glyphicon glyphicon-sort"></span>
        <span class="text-muted">Сортування</span>
        <span class="current"><?= $searchModel->getSortingLabel(\Yii::$app->request->get('sort', $searchModel::SORT_PURCHASED_ASC)) ?></span>
        <span class="caret"></span>
    </button>
    <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="sorting_dLabel">
<?php foreach ($searchModel->getSorting() as $key => $label): ?>
        <li class="<?php if (\Yii::$app->request->get('sort') === $key): ?>active<?php endif;?>">
            <?= Html::a($label, Url::current(['sort' => $key]), ['data-pjax' => 1]) ?>
        </li>
<?php endforeach;?>
    </ul>
</div>