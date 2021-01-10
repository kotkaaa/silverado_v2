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
/* @var $model \common\models\Order */
/* @var $form yii\widgets\ActiveForm */

OrderAsset::register($this);
?>

<div class="order-form">

    <?php $form = ActiveForm::begin(['id' => 'checkoutForm']); ?>

    <ul class="nav nav-tabs" role="tablist">
        <li role="presentation" class="active">
            <a href="#main" aria-controls="main" role="tab" data-toggle="tab">Main</a>
        </li>
        <li role="presentation">
            <a href="#products" aria-controls="products" role="tab" data-toggle="tab">Products</a>
        </li>
    </ul>

    <br>

    <div class="tab-content">
        <div role="tabpanel" class="tab-pane active" id="main">
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
        </div>

        <div role="tabpanel" class="tab-pane " id="products">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Sku</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
<?php foreach ($model->orderProducts as $orderProduct): ?>
                    <tr data-key="0">
                        <td>
                            <?= Html::hiddenInput(Html::getInputName($orderProduct, 'title'), $orderProduct->title) ?>
                            <?= $orderProduct->title ?>
                        </td>
                        <td>
                            <?= Html::hiddenInput(Html::getInputName($orderProduct, 'sku'), $orderProduct->sku) ?>
                            <?= $orderProduct->sku ?>
                        </td>
                        <td>
                            <div class="input-group">
                                <span class="input-group-btn">
                                    <button class="btn btn-default" type="button" onclick="decreasePosition();">-</button>
                                </span>
                                <?= Html::textInput(Html::getInputName($orderProduct, 'quantity'), $orderProduct->quantity, [
                                    'type' => 'nubmer',
                                    'class' => 'form-control text-center',
                                    'min' => 1,
                                    'max' => 100
                                ]) ?>
                                <span class="input-group-btn">
                                    <button class="btn btn-default" type="button" onclick="increasePosition();">+</button>
                                </span>
                            </div>
                        </td>
                        <td>
                            <?= Html::hiddenInput(Html::getInputName($orderProduct, 'price'), $orderProduct->price) ?>
                            <?= \Yii::$app->formatter->asCurrency($orderProduct->price) ?>
                        </td>
                        <td>
                            <?=  \Yii::$app->formatter->asCurrency($orderProduct->price * $orderProduct->quantity ?? 1) ?>
                        </td>
                    </tr>
<?php endforeach;?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
