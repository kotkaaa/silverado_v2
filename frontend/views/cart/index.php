<?php

use yii\helpers\Url;
use yii\helpers\Html;
use kartik\form\ActiveForm;
use kartik\select2\Select2;
use yii\web\JsExpression;
use common\models\OrderInfo;
use frontend\assets\CartAsset;

/** @var \frontend\models\OrderForm $orderForm */
/** @var \yii\web\View $this */

CartAsset::register($this);
?>

<?php $form = ActiveForm::begin(['id' => 'checkoutForm']) ?>

<?= $form->field($orderForm, 'user_name')->textInput() ?>

<?= $form->field($orderForm, 'user_email')->textInput() ?>

<?= $form->field($orderForm, 'user_phone')->textInput() ?>

<?= $form->field($orderForm, 'payment_type')->radioButtonGroup(OrderInfo::paymentList()) ?>

<?= $form->field($orderForm, 'delivery_type')->radioButtonGroup(OrderInfo::deliveryList(), ['itemOptions' => [
    'onchange' => new JsExpression('return Checkout.swithcDeliveryType(this.value);')
]]) ?>

<?= $form->field($orderForm, 'location')->widget(Select2::className(), [
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
            'url' => Url::to('/cart/search-city'),
            'dataType' => 'json',
            'data' => new JsExpression('function(params) { return {term:params.term}; }')
        ],
        'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
        'templateResult' => new JsExpression('function(result) { return result.text; }'),
        'templateSelection' => new JsExpression('function (result) { return result.text; }'),
    ],
    'pluginEvents' => [
        'change.select2' => new JsExpression('function(e) {return Checkout.getWareHouses(e.target.value);}'),
        'select2:clear' => new JsExpression('function(e) {return Checkout.clearWareHouses();}')
    ]
]) ?>

<?= $form->field($orderForm, 'address_1')->widget(Select2::className(), [
    'options' => [
        'multiple' => false,
        'placeholder' => '-- Select --'
    ],
    'pluginOptions' => [
        'allowClear' => true
    ],
]) ?>

<?= $form->field($orderForm, 'address_2')->textInput(['maxlength' => true]) ?>

<?= $form->field($orderForm, 'custom_recepient')->checkbox([
    'onchange' => new JsExpression('return Checkout.customizeRecepient(this.checked)')
]) ?>

<?= $form->field($orderForm, 'recepient_name')->textInput() ?>

<?= $form->field($orderForm, 'recepient_phone')->textInput() ?>

<?= $form->field($orderForm, 'comment')->textarea(['rows' => 5]) ?>

<?= Html::submitButton('Оформить заказ', ['class' => 'btn btn-success btn-lg']) ?>

<?php ActiveForm::end() ?>