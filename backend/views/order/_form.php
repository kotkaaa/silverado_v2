<?php

use yii\helpers\Url;
use yii\helpers\Html;
use kartik\form\ActiveForm;
use common\models\Order;
use common\models\OrderInfo;
use kartik\select2\Select2;
use yii\web\JsExpression;
use backend\assets\OrderAsset;

/* @var $this yii\web\View */
/* @var $orderForm \backend\models\OrderForm */
/* @var $form yii\widgets\ActiveForm */

OrderAsset::register($this);
?>

<div class="order-form">

    <?php $form = ActiveForm::begin(['id' => 'checkoutForm']); ?>

    <?= $form->field($orderForm, 'status')->dropDownList(Order::statusList(), ['prompt' => '-- Select --']) ?>

    <?= $form->field($orderForm, 'source')->dropDownList(Order::sourceList(), ['prompt' => '-- Select --']) ?>

    <hr>
    
    <h3>Info</h3>
    
    <?= $form->field($orderForm, 'user_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($orderForm, 'user_phone')->textInput(['maxlength' => true]) ?>

    <?= $form->field($orderForm, 'user_email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($orderForm, 'comment')->textarea(['rows' => 8]) ?>

    <h3>Delivery</h3>

    <hr>

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
                'url' => Url::to('/order/search-city'),
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

    <?= $form->field($orderForm, 'custom_recepient')->checkbox() ?>

    <?= $form->field($orderForm, 'recepient_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($orderForm, 'recepient_phone')->textInput(['maxlength' => true]) ?>

    <h3>Payment</h3>

    <hr>

    <?= $form->field($orderForm, 'payment_type')->radioButtonGroup(OrderInfo::paymentList())->label(false) ?>

    <h3>Additional</h3>

    <hr>

    <?= $form->field($orderForm, 'note')->textarea(['rows' => 8]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
