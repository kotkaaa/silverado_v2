<?php

use yii\helpers\Html;
use yii\widgets\ListView;

/** @var \yii\web\View $this **/
/** @var \common\models\Category $model */
/** @var \yii\data\DataProviderInterface $dataProvider */

$this->title = $model->meta_title;
?>

<h1><?= $model->title ?></h1>

<?= ListView::widget([
    'dataProvider' => $dataProvider,
    'itemView' => '_product-item',
    'summary' => ''
]) ?>

<hr>

<?= Html::decode($model->description) ?>
