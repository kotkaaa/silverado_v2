$(window).on('load', function () {
    var $slider = new Swiper('.product__slider-bestsellers', {
        observer: true,
        autoHeight: false,
        freeMode: false,
        slidesPerView: 1,
        slidesPerGroup: 1,
        spaceBetween: 10,
        watchSlidesVisibility: true,
        paginationClickable: true,
        pagination: {
            clickable: true,
            el: '.bestsellers-swiper-pagination'
        },
        breakpoints: {
            480: {
                slidesPerView: 2
            },
            768: {
                slidesPerView: 3
            },
            1024: {
                slidesPerView: 4
            }
        }
    });
});