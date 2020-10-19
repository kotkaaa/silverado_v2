<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\OptionValue */

$this->title = 'Create Option Value';
$this->params['breadcrumbs'][] = ['label' => 'Option Values', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="option-value-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
