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
