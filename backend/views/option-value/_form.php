<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\web\JsExpression;
use common\models\OptionValue;

/* @var $this yii\web\View */
/* @var $model common\models\OptionValue */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="option-value-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'option_uuid')->widget(Select2::class, [
        'initValueText' => $model->option ? $model->option->title : null,
        'options' => [
            'multiple' => false,
            'placeholder' => '-- Select --'
        ],
        'pluginOptions' => [
            'allowClear' => true,
            'minimumInputLength' => 3,
            'language' => [
                'errorLoading' => new JsExpression("function () { return 'Waiting for results...'; }"),
            ],
            'ajax' => [
                'url' => Url::to('/option/search'),
                'dataType' => 'json',
                'data' => new JsExpression('function(params) { return {term:params.term}; }')
            ],
            'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
            'templateResult' => new JsExpression('function(result) { return result.text; }'),
            'templateSelection' => new JsExpression('function (result) { return result.text; }'),
        ],
    ]) ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'alias')->textInput([
        'readonly' => true,
        'ondblclick' => new JsExpression('function(){this.readonly = false;}')
    ]) ?>

    <?= $form->field($model, 'price')->textInput() ?>

    <?= $form->field($model, 'action')->dropDownList(OptionValue::actionsList(true), ['prompt' => '-- Select --']) ?>

    <?= $form->field($model, 'position')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
