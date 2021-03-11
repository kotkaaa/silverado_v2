
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
});

