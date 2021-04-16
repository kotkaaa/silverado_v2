<?php

use frontend\assets\HomeAsset;

/* @var $this yii\web\View */

$this->title = 'My Yii Application';

HomeAsset::register($this);
?>

<div class="home__slider">
    <div class="swiper-wrapper">
        <div class="swiper-slide">
            <img src="/img/Slider-1.jpg" alt="">
        </div>
        <div class="swiper-slide">
            <img src="/img/Slider-2.jpg" alt="">
        </div>
        <div class="swiper-slide">
            <img src="/img/Slider-3.jpg" alt="">
        </div>
    </div>

    <div class="swiper-pagination home-swiper-pagination"></div>
</div>

<?= \frontend\widgets\selections\bestsellers\BestSellers::widget() ?>

<div class="banners">
    <div class="banner-col eq-0">
        <div class="banner-row">
            <a href="#">
                <img src="/img/banner-1.jpg" alt="">
            </a>
        </div>

        <div class="banner-row">
            <a href="#">
                <img src="/img/banner-2.jpg" alt="">
            </a>
        </div>
    </div>

    <div class="banner-col eq-1">
        <a href="#">
            <img src="/img/banner-3.jpg" alt="">
        </a>
    </div>

    <div class="banner-col eq-2">
        <a href="#">
            <img src="/img/banner-4.jpg" alt="">
        </a>
    </div>
</div>

<div class="collections">
    <h2>Колекції</h2>

    <div class="swiper-container collection__slider">
        <div class="swiper-wrapper">
            <div class="swiper-slide collection-item">
                <div class="short">
                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore</p>
                    <img src="/img/collection-1.jpg" alt="">
                </div>

                <div class="desc">
                    <h3>Колекція “Паттайа”</h3>
                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
                    <p>Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id</p>
                </div>
            </div>

            <div class="swiper-slide collection-item">
                <div class="short">
                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore</p>
                    <img src="/img/collection-1.jpg" alt="">
                </div>

                <div class="desc">
                    <h3>Колекція “Паттайа”</h3>
                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
                    <p>Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id</p>
                </div>
            </div>

            <div class="swiper-slide collection-item">
                <div class="short">
                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore</p>
                    <img src="/img/collection-1.jpg" alt="">
                </div>

                <div class="desc">
                    <h3>Колекція “Паттайа”</h3>
                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
                    <p>Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id</p>
                </div>
            </div>
        </div>

        <div class="swiper-pagination collection-swiper-pagination"></div>
    </div>
</div>