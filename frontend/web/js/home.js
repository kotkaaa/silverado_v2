
$(window).on('load', function () {
    var $homeSlider = new Swiper('.home__slider', {
        autoHeight: true,
        observer: true,
        paginationClickable: true,
        pagination: {
            clickable: true,
            el: '.home-swiper-pagination'
        }
    });

    var $collectionSlider = new Swiper('.collection__slider', {
        autoHeight: true,
        observer: true,
        paginationClickable: true,
        pagination: {
            clickable: true,
            el: '.collection-swiper-pagination'
        },
        effect: 'fade',
        fadeEffect: {
            crossFade: true
        },
    });
});

