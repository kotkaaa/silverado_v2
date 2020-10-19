<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\Option;

/* @var $this yii\web\View */
/* @var $model common\models\Option */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="option-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'strategy')->dropDownList(Option::strategyList(), ['prompt' => '-- Select --']) ?>

    <?= $form->field($model, 'position')->textInput() ?>

    <?= $form->field($model, 'required')->checkbox() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
