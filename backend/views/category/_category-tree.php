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
    <label class="control-label" for="support-parent_uuid">Parent</label>
    <select id="<?= Html::getInputId($model, 'parent_uuid') ?>" class="form-control" name="<?= Html::getInputName($model, 'parent_uuid') ?>">
        <option value>-- Select --</option>
<?php endif;?>
<?php foreach ($categoryTree as $category) : ?>
        <?php if ($model->uuid == $category->uuid) continue; ?>
        <option value="<?= $category->uuid ?>" <?php if ($model->parent_uuid == $category->uuid): ?>selected<?php endif;?>><?= $prefix . $category->title ?></option>
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
