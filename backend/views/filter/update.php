<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Filter */

$this->title = 'Update Filter: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Filters', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->uuid]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="filter-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
