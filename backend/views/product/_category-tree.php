<?php

use yii\helpers\Html;

/** @var \common\models\Category[] $categoryTree */
/** @var \common\models\Category $model */
/** @var int $level */
/** @var \yii\web\View $this */

$prefix = str_repeat('-- ', $level);
?>

<?php if (!$level): ?>
<div class="form-group field-support-parent_uuid">
    <label class="control-label" for="support-parent_uuid">Category</label>
    <select id="<?= Html::getInputId($model, 'category_uuid') ?>" class="form-control" name="<?= Html::getInputName($model, 'category_uuid') ?>">
        <option value>-- Select --</option>
<?php endif;?>
<?php foreach ($categoryTree as $category) : ?>
        <option value="<?= $category->uuid ?>" <?php if ($model->category_uuid == $category->uuid): ?>selected<?php endif;?> <?php if ($category->separator): ?>disabled<?php endif;?>><?= $prefix . $category->title ?></option>
<?php if (!empty($category->children)) : ?>
            <?= $this->render('_category-tree', [
                'categoryTree' => $category->children,
                'model' => $model,
                'level' => $level + 1
            ]) ?>
<?php endif;?>
<?php endforeach;?>
<?php if (!$level): ?>
    </select>
    <div class="help-block"></div>
</div>
<?php endif;?>
