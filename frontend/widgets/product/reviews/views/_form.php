<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\Pjax;
use kartik\form\ActiveForm;
use kartik\rating\StarRating;

/** @var \common\models\Review $model */
?>

<?php Pjax::begin(['enablePushState' => false, 'timeout' => 5000]) ?>

<?php if (\Yii::$app->session->hasFlash('reviewSuccess')): ?>
<div class="product-review__success text-center">
    <p><i class="glyphicon glyphicon-envelope"></i></p>

    <p><?= \Yii::$app->session->getFlash('reviewSuccess') ?></p>
</div>
<?php else:?>
<p class="text-center">
    <?= Html::button('Залишити відгук', [
        'class' => 'btn btn-default btn-lg',
        'data' => [
            'pjax' => false,
            'toggle' => 'collapse',
            'target' => '#reviewForm'
        ],
        'aria' => [
            'expanded' => false,
            'controls' => 'reviewForm'
        ]
    ]) ?>
</p>

<?php $form = ActiveForm::begin(['id' => 'reviewForm', 'action' => Url::current(), 'options' => [
    'class' => 'collapse',
    'enctype' => 'multipart/form-data',
    'data-pjax' => true
]]) ?>

<?= $form->field($model, 'author', ['template' => '{input}{error}'])->textInput([
    'maxlength' => true,
    'placeholder' => 'Ваше ім\'я',
    'class' => 'form-control input-lg'
]) ?>

<?= $form->field($model, 'comment', ['template' => '{input}{error}'])->textarea([
    'rows' => 4,
    'placeholder' => 'Коментар (до 1000 символів)',
    'class' => 'form-control input-lg'
]) ?>

<?= $form->field($model, '_upload', ['template' => '<label>{input}</label><div class="preview"></div>'])->fileInput([
    'multiple' => true,
    'accept' => 'image/*'
]) ?>


<div class="submit">
    <?= Html::submitButton('Надіслати <i class="glyphicon glyphicon-send"></i>', ['class' => 'btn btn-default btn-lg']) ?>

    <?= $form->field($model, 'rate')->widget(StarRating::class, [
        'pluginOptions' => [
            'stars' => 5,
            'min' => 0,
            'max' => 5,
            'step' => 1,
            'showClear' => false,
            'showCaption' => false,
            'size' => 'm',
            'theme' => 'krajee-uni'
        ]
    ])->label('Оцінка') ?>
</div>



<?php ActiveForm::end() ?>
<?php endif;?>

<?php Pjax::end() ?>
