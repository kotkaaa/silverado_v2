<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\Pjax;
use kartik\form\ActiveForm;
use yii\widgets\MaskedInput;
use yii\web\JsExpression;
use aneeshikmat\yii2\Yii2TimerCountDown\Yii2TimerCountDown;
use yii\bootstrap\Modal;

/** @var \frontend\widgets\callback\models\CallbackForm $model */
/** @var \common\classes\Request\CallbackRequest $request */
?>

<div class="callback dropdown">
    <a class="tel" href="#" data-toggle="modal" data-target="#callbackModal">096 05 49 542</a>

    <button class="icon" data-toggle="modal" data-target="#callbackModal">
        <svg version="1.1" height="28" width="28" baseProfile="basic" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 512 512" xml:space="preserve">
            <path d="M366,0H146c-20.7,0-37.5,16.8-37.5,37.5v437c0,20.7,16.8,37.5,37.5,37.5h220c20.7,0,37.5-16.8,37.5-37.5v-437C403.5,16.8,386.7,0,366,0z M388.5,407H176c-4.1,0-7.5,3.4-7.5,7.5c0,4.1,3.4,7.5,7.5,7.5h212.5v52.5c0,12.4-10.1,22.5-22.5,22.5H146c-12.4,0-22.5-10.1-22.5-22.5V422H146c4.1,0,7.5-3.4,7.5-7.5c0-4.1-3.4-7.5-7.5-7.5h-22.5V75h265V407z M388.5,60h-265V37.5c0-12.4,10.1-22.5,22.5-22.5h220c12.4,0,22.5,10.1,22.5,22.5V60z"/>
            <path d="M286,30h-30c-4.1,0-7.5,3.4-7.5,7.5c0,4.1,3.4,7.5,7.5,7.5h30c4.1,0,7.5-3.4,7.5-7.5C293.5,33.4,290.1,30,286,30z"/>
            <path d="M256,482c12.4,0,22.5-10.1,22.5-22.5c0-12.4-10.1-22.5-22.5-22.5c-12.4,0-22.5,10.1-22.5,22.5C233.5,471.9,243.6,482,256,482z M256,452c4.1,0,7.5,3.4,7.5,7.5s-3.4,7.5-7.5,7.5s-7.5-3.4-7.5-7.5S251.9,452,256,452z"/>
            <circle cx="226" cy="37.5" r="7.5"/>
        </svg>
    </button>
</div>

<?php Modal::begin(['id' => 'callbackModal', 'size' => Modal::SIZE_SMALL]) ?>
<div class="callback-modal-dialog text-center">
    <?php Pjax::begin(['id' => 'callbackPjax', 'timeout' => 10000, 'enablePushState' => false, 'enableReplaceState' => false]) ?>
    <?php if ($request): ?>
        <p class="">Очікуйте на дзвінок через</p>

        <div id="time-down-counter-<?= $request->id ?>" class="count-down text-center"></div>

        <?= Yii2TimerCountDown::widget([
            'countDownIdSelector' => 'time-down-counter-' . $request->id,
            'countDownDate' => $request->expires_at * 1000,
            'countDownResSperator' => ':',
            'addSpanForResult' => false,
            'addSpanForEachNum' => true,
            'countDownReturnData' => 'from-minutes',
            'templateStyle' => 2,
            'callBack' => new JsExpression('$.pjax.reload({container:"#callbackPjax", async:false});')
        ]) ?>
    <?php else: ?>
        <h3>Ми на зв'язку</h3>

        <p class="">Зателефонуйте</p>

        <p class="text-center">
            <a href="tel:+380960549542" class="btn btn-link btn-lg" data-pjax="0">096 05 49 542</a>
        </p>

        <p class="">або ми зателефонуємо вам</p>

        <?php $form = ActiveForm::begin(['action' => Url::toRoute('site/callback'), 'options' => ['data-pjax' => true]]) ?>

        <?= $form->field($model, 'name')->textInput(['class' => 'form-control input-lg']) ?>

        <?= $form->field($model, 'phone')->widget(MaskedInput::class, [
            'mask' => '+389999999999',
            'clientOptions' => ['greedy' => false],
            'options' => [
                'class' => 'form-control input-lg'
            ]
        ]) ?>

        <?= Html::submitButton('Замовити дзвінок', ['class' => 'btn btn-default btn-lg']) ?>
        <?php ActiveForm::end() ?>
    <?php endif;?>
    <?php Pjax::end() ?>
</div>
<?php Modal::end() ?>