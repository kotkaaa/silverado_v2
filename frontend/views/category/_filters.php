<?php

/** @var \common\models\Filter[] $filters */
?>

<?php foreach ($filters as $filter): ?>
    <?php if (!$filter->strategy->getCount()) continue;?>
    <h3><?= $filter->title ?></h3>
<?php foreach ($filter->strategy->getValues() as $value): ?>
        <a href="<?= $value->url ?>">
            <?= $value->label ?>
<?php if ($value->checked): ?>
            <span class="glyphicon glyphicon-remove text-danger"></span>
<?php endif;?>
        </a><br>
<?php endforeach;?>
<?php endforeach;?>
