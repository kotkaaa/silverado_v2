<?php

use yii\helpers\Html;

/** @var \common\models\Product $model */
/** @var \yii\web\View $this */
?>

<div class="product-card__image">
    <div class="preview swiper-container" id="image-preview">
        <div class="swiper-wrapper">
<?php foreach ($model->files as $file): ?>
            <div class="swiper-slide">
<?php if (preg_match('/\.(mov|avi|mp4)$/i', $file->name)): ?>
                <?= Html::tag('video', '<source src="/' . $file->url . '/' . $file->name . '">', ['crossorigin' => true, 'controls' => true, 'width' => '100%']) ?>
<?php else:?>
                <?= Html::img('/' . $file->url . '/preview-' . $file->name) ?>
<?php endif;?>
            </div>
<?php endforeach;?>
        </div>
    </div>

    <div class="thumbnails swiper-container" id="image-thumbnails">
        <div class="swiper-wrapper">
<?php foreach ($model->files as $i => $file): ?>
            <div class="swiper-slide">
<?php if (preg_match('/\.(mov|avi|mp4)$/i', $file->name)): ?>
                <?= Html::img('/img/video-player.svg') ?>
<?php else:?>
                <?= Html::img('/' . $file->url . '/small-' . $file->name) ?>
<?php endif;?>
            </div>
<?php endforeach;?>
        </div>
    </div>
</div>