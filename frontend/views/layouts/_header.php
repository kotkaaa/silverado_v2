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
            <div class="callback dropdown">
                <button class="icon">
                    <svg version="1.1" height="28" width="28" baseProfile="basic" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 512 512" xml:space="preserve">
                        <path d="M366,0H146c-20.7,0-37.5,16.8-37.5,37.5v437c0,20.7,16.8,37.5,37.5,37.5h220c20.7,0,37.5-16.8,37.5-37.5v-437C403.5,16.8,386.7,0,366,0z M388.5,407H176c-4.1,0-7.5,3.4-7.5,7.5c0,4.1,3.4,7.5,7.5,7.5h212.5v52.5c0,12.4-10.1,22.5-22.5,22.5H146c-12.4,0-22.5-10.1-22.5-22.5V422H146c4.1,0,7.5-3.4,7.5-7.5c0-4.1-3.4-7.5-7.5-7.5h-22.5V75h265V407z M388.5,60h-265V37.5c0-12.4,10.1-22.5,22.5-22.5h220c12.4,0,22.5,10.1,22.5,22.5V60z"/>
                        <path d="M286,30h-30c-4.1,0-7.5,3.4-7.5,7.5c0,4.1,3.4,7.5,7.5,7.5h30c4.1,0,7.5-3.4,7.5-7.5C293.5,33.4,290.1,30,286,30z"/>
                        <path d="M256,482c12.4,0,22.5-10.1,22.5-22.5c0-12.4-10.1-22.5-22.5-22.5c-12.4,0-22.5,10.1-22.5,22.5C233.5,471.9,243.6,482,256,482z M256,452c4.1,0,7.5,3.4,7.5,7.5s-3.4,7.5-7.5,7.5s-7.5-3.4-7.5-7.5S251.9,452,256,452z"/>
                        <circle cx="226" cy="37.5" r="7.5"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>
</header>
