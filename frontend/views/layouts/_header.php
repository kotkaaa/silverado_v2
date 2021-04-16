<?php

use frontend\widgets\menu\MainMenu;

/** @var \yii\web\View $this */
?>

<header class="header-container">
    <div class="container-fluid">
        <div class="header-left">
            <a href="/" class="logo-image">
                <img src="/img/silverado.svg" alt="Silverado">
            </a>

            <span class="separator"></span>

            <?= MainMenu::widget() ?>
        </div>
        <div class="header-right">
            <button class="mapbox"></button>

            <button class="minicart" id="minicart">
                <span class="cnt">5</span>
            </button>

            <span class="separator"></span>

            <?= \frontend\widgets\callback\CallbackWidget::widget() ?>
        </div>
    </div>
</header>
