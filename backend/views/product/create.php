<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Product */
/* @var $categoryTree \common\models\Category[] */
/* @var $options \common\models\Option[] */
/* @var $attributes \common\models\Attribute[] */

$this->title = 'Create Product';
$this->params['breadcrumbs'][] = ['label' => 'Products', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'categoryTree' => $categoryTree,
        'options' => $options,
        'attributes' => $attributes
    ]) ?>

</div>
