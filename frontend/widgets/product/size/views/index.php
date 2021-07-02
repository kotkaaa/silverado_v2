<?php

use yii\bootstrap\Modal;

/** @var \yii\web\View $this */

$js = <<<JS
    var SizeChecker = {
        inputField: null,
        outputField: null,
        init: function () {
            var self = this;
            self.inputField = $('#size-checker__input-in');
            self.outputField = $('#size-checker__input-out');
            
            self.inputField.on('change blur', function () {
                self.recalc(); 
            });
        },
        recalc: function () {
            var self = this,
                inVal = self.inputField.val() || null,
                outVal = inVal ? (inVal / Math.PI).toFixed(1) : null,
                decimal = (outVal - Math.floor(outVal)).toFixed(1);
            
            if (decimal && decimal <= 0.5) {
                decimal = (0.5 - decimal).toFixed(1);
            } else if (decimal) {
                decimal = (1 - decimal).toFixed(1);
            }
            
            outVal = parseFloat(outVal) + parseFloat(decimal);
            
            self.outputField.val(outVal);
        }
    };

    window.addEventListener('load', SizeChecker.init(), false);
JS;

$this->registerJs($js, \yii\web\View::POS_READY);
?>

<div class="size-checker__form">
    <h3>Дізнайся свій розмір
    </h3>

    <div class="form">
        <div class="inna">
            <label>Окружність<br>пальця
                <?php Modal::begin([
                    'toggleButton' => [
                        'tag' => 'a',
                        'label' => '',
                        'class' => 'glyphicon glyphicon-info-sign'
                    ],
                    'bodyOptions' => [
                        'style' => [
                            'padding-left' => '2em',
                            'padding-right' => '2em',
                            'padding-bottom' => '2em'
                        ]
                    ]
                ]) ?>
                <h3 class="text-muted">Як дізнатися розмір каблучки?</h3>

                <ol class="text-muted">
                    <li>Виміряйте рулеткою окружність пальця (або виміяйте ниткою і прикладіть нитку до лінійки)</li>
                    <li>Отримане число введіть в текстове поле калькулятора</li>
                    <li>Калькулятор автоматично розрахує необхідний вам розмір</li>
                </ol>

                <p class="text-danger">Увага! Для каблучок з широким обідком рекомендуємо обирати розмір на 0,5 більше аніж зазвичай.</p>
                <?php Modal::end() ?>
            </label>
            <span class="add-on">
                <input type="number" id="size-checker__input-in">
            </span>
        </div>

        <div class="divider"></div>

        <div class="outta">
            <label>Розмір<br>каблучки</label>
            <input type="number" id="size-checker__input-out" readonly>
        </div>
    </div>
</div>
