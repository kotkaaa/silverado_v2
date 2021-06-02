<?php

/** @var \common\models\Filter[] $filters */
?>

<div class="filters">
    <button class="btn btn-link toggle-off" onclick="$('.filters').toggleClass('toggle-on');">X</button>
<?php foreach ($filters as $filter): ?>
    <?php if (!$filter->strategy->getCount()) continue;?>

    <a href="#<?= $filter->uuid ?>"
       class="filter-group-toggle <?php if (!$filter->strategy->getCountChecked()): ?>collapsed<?php endif;?>"
       role="button"
       data-toggle="collapse"
       data-pjax="0"
       aria-expanded="<?php if ($filter->strategy->getCountChecked()): ?>true<?php else: ?>false<?php endif;?>"
       aria-controls="<?= $filter->uuid ?>">
        <?= $filter->title ?>
    </a>

    <div class="filter-group collapse <?php if ($filter->strategy->getCountChecked()): ?>in<?php endif;?>" id="<?= $filter->uuid ?>">
<?php foreach ($filter->strategy->getValues() as $value): ?>
        <a href="<?= $value->url ?>" class="<?php if ($value->checked): ?>checked<?php endif;?>">
            <?= $value->label ?>
        </a>
<?php endforeach;?>
    </div>
<?php endforeach;?>
</div>
