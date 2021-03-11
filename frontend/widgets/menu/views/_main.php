<?php

use yii\helpers\Html;

/** @var \common\models\Category[] $items */
?>

<div class="main-menu">
    <button class="main-menu-toggle">
        <span class="menu-sm"></span>
        <span class="menu-sm"></span>
        <span class="menu-sm"></span>
    </button>

    <ul class="main-menu-items">
<?php foreach ($items as $item): ?>
        <li class="main-menu-item">
            <?= Html::a($item->title, ['/category/' . $item->alias]) ?>
        </li>
<?php endforeach;?>
    </ul>
</div>
