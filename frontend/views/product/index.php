<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use kartik\form\ActiveForm;

/** @var yii\web\View $this **/
/** @var \common\models\Product $model */

$this->title = $model->title;
$this->registerMetaTag(['name' => 'description', 'content' => $model->meta_description]);
$this->registerMetaTag(['name' => 'keywords', 'content' => $model->meta_keywords]);
$this->registerMetaTag(['name' => 'robots', 'content' => $model->meta_robots]);
?>
<h1><?= $model->title ?></h1>

<p>
    <?= Html::img('/uploads/product/preview-' . $model->_preview->name, ['class' => 'img-thumbnail']) ?>
</p>

<p>
<?php foreach ($model->files as $i => $file): ?>
    <?= Html::img('/uploads/product/thumb-' . $file->name, ['class' => !$i ? 'img-rounded' : 'img-thumbnail']) ?>
<?php endforeach;?>
</p>

<p>
    <?= \Yii::$app->formatter->asCurrency($model->price) ?>
</p>

<?php $form = ActiveForm::begin(['action' => Url::to(['/cart/add/' . $model->alias]), 'method' => 'POST']) ?>

<?php foreach ($model->options as $option): ?>
    <?= $form->field($model, "selectedOptions[{$option->uuid}][]")->radioList(ArrayHelper::map($model->optionValues, 'uuid', 'title'), ['unselect' => null])->label($option->title) ?>
<?php endforeach;?>

<p>
    <?= Html::submitButton('Купить', ['class' => 'btn btn-success']) ?>
</p>

<?php ActiveForm::end() ?>

<p>Артикул: <strong><?= $model->sku ?></strong></p>

<?php foreach ($model->attributeModels as $attribute): ?>
<p>
    <?= $attribute->title ?>:
<?php foreach ($attribute->values as $i => $value): ?>
    <?php if(!in_array($value->uuid, $model->_attributes)) continue;?>
    <?php if($i): ?>, <?php endif;?><strong><?= $value->title ?></strong>
<?php endforeach;?>
</p>
<?php endforeach;?>
