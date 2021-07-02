<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\Pjax;
use kartik\form\ActiveForm;

/** @var \common\models\Product $model */
/** @var \yii\web\View $this */
?>

<div class="product-card__details">
    <div class="product-card__stock-info">
        <span class="sku">Артикул: <?= $model->sku ?></span>

        <span class="present">Є у наявності - доставка 2-3 дні</span>
    </div>

    <?php Pjax::begin(['id' => $model->uuid . '_pjax', 'enablePushState' => false, 'enableReplaceState' => false, 'timeout' => false, 'clientOptions' => ['async' => false]]) ?>

    <?php $form = ActiveForm::begin([
        'id' => $model->uuid . '_form',
        'action' => Url::to(['/cart/add/' . $model->alias]),
        'method' => 'POST',
        'options' => [
            'data-pjax' => 1
        ]
    ]) ?>

    <div class="product-card__options">
<?php foreach ($model->options as $option): ?>
        <div class="option">
            <?= $form->field($model, "selectedOptions[{$option->uuid}][]")
                ->radioButtonGroup(ArrayHelper::map($model->optionValues, 'uuid', 'title'), ['unselect' => null])
                ->label($option->title) ?>
<?php if ($option->plugin): ?>
            <div class="divider"></div>
            <?= (\Yii::createObject($option->plugin))::widget() ?>
<?php endif;?>
        </div>
<?php endforeach;?>
    </div>

    <div class="product-card__buy">
        <p class="price">
<?php if ($model->oldPrice): ?>
            <span class="strike">
                <?= \Yii::$app->formatter->asCurrency($model->oldPrice) ?>
            </span><br>
<?php endif;?>
            <?= \Yii::$app->formatter->asCurrency($model->price) ?>
        </p>

        <div class="divider"></div>

        <?= Html::submitButton('До кошика', ['class' => 'btn btn-default btn-lg']) ?>
    </div>

    <?php ActiveForm::end() ?>

    <?php Pjax::end() ?>

    <div class="product-card__attributes">
        <h4>Характеристики:</h4>
<?php foreach ($model->attributeModels as $attribute): ?>
        <p>
            <span class="text-muted"><?= $attribute->title ?>:</span>
            <?php $values = [] ?>
<?php foreach ($attribute->values as $i => $value): ?>
            <?php if(!in_array($value->uuid, $model->_attributes)) continue;?>
            <?php $values[] = $value->title ?>
<?php endforeach;?>
            <?= implode(', ', $values) ?>
        </p>
<?php endforeach;?>
    </div>
</div>