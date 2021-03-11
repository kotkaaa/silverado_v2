
$('.main-menu').on('click', function (e) {
    e.stopPropagation();
});

$('body').on('click', function () {
    $('.main-menu').removeClass('expand');
});

$('.main-menu').on('click', '.main-menu-toggle', function (e) {
    e.preventDefault();
    $(e.target).closest('.main-menu').toggleClass('expand');
});

$(window).on('scroll', function () {
    var scroll = verge.scrollY();

    if (scroll > 0) {
        $(".header-container").addClass("scroll-in");
    } else {
        $(".header-container").removeClass("scroll-in");
    }
});