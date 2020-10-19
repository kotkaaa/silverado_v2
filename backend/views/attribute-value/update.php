<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\AttributeValue */

$this->title = 'Update Attribute Value: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Attribute Values', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->uuid]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="attribute-value-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
