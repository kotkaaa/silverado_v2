<?php

use yii\bootstrap\Modal;
?>

<p class="text-muted">Залиште першим відгук про цей товар та отримайте знижку!</p>

<p>
    <?php Modal::begin(['toggleButton' => ['class' => 'btn btn-link btn-sm', 'label' => 'Умови акції']]) ?>
        <h3 class="text-center">Умови акції</h3>

        <p class="text-left">
            1. Для участі в Акції вам необхідно бути дійсним покупцем нашого інтернет-магазину.
        </p>

        <p class="alert alert-success text-left">
            Просто придбайте у нас будь-який товар)
        </p>

        <p class="text-left">
            2. Додайте відгук з фото про придбаний товар.
        </p>

        <p class="alert alert-info text-left">
            Важливо!
            Обов'язково вкажіть справжнє ім'я, під яким Ви робили замовлення та додайте фото придбаного у нас товару.
            Відгук має бути саме про той товар, який ви придбали у нас. Фото має відповідати придбаному товару.
        </p>

        <p class="text-left">
            3. Знижка <strong class="text-danger">-5%</strong> діє на наступну покупку у нашому інтернет-магазині. Для отримання знижки,
            у коментар до замовлення додайте посилання на сторінку з відгуком.
        </p>

        <p class="alert alert-warning text-left">
            Для отримання акційної знижки Ви маєте бути першим хто залишить відгук про товар, виконавши всі умови Акції.
            Якщо інший учасник залишив відгук раніше Вас, не виконавши умов акції - переможцем будете Ви!
        </p>

        <p class="text-left">
            4. Ми публікуємо всі відгуки: як позитивні так і негативні, тому ваша знижка гарантована, незалежно від
            змісту і якості фото та діє у сумі з усіма іншими знижками та акційними пропозиціями ;)
        </p>

        <p class="alert alert-danger text-left">
            Увага!
            У відгуках заборонена лайка, нецензурні вислови, спам, реклама та посилання на сторонні ресурси.
            Відгуки що не відповідають вимогам можуть бути видалені модератором та участі в Акції не приймають
        </p>
    <?php Modal::end() ?>
</p>