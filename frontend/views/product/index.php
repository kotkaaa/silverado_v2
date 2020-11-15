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
    <?php if (preg_match('/^image+/i', $model->_preview->mime)): ?>
        <?= Html::img('/' . $model->_preview->url . '/preview-' . $model->_preview->name, ['class' => 'img-thumbnail']) ?>
    <?php else:?>
        <video controls crossorigin="anonymous" width="550" height="550">
            <source src="<?= '/' . $model->_preview->url . '/' . $model->_preview->name ?>">
        </video>
    <?php endif;?>
</p>

<p>
<?php foreach ($model->files as $i => $file): ?>
    <?php if (preg_match('/^image+/i', $file->mime)): ?>
        <?= Html::img('/' . $file->url . '/small-' . $file->name, ['class' => !$i ? 'img-rounded' : 'img-thumbnail']) ?>
    <?php else:?>
        <?= Html::img('/img/video-player.svg', ['class' => !$i ? 'img-rounded' : 'img-thumbnail', 'width' => 120, 'height' => 120]) ?>
    <?php endif;?>
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
