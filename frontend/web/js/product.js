
$(function () {
    var Swiper1 = new Swiper('#image-thumbnails', {
        loop: false,
        spaceBetween: 10,
        slidesPerView: 4,
        freeMode: true,
        watchSlidesVisibility: true,
        watchSlidesProgress: true
    });

    var Swiper2 = new Swiper('#image-preview', {
        loop: false,
        spaceBetween: 0,
        slidesPerView: 1,
        autoHeight: false,
        thumbs: {
            swiper: Swiper1
        }
    });

    $('.product-review').on('change', 'input[type="file"]', function (event) {
        var preview = $(this).closest('.form-group').find('.preview'),
            files = this.files;

        preview.html('');

        for (var i = 0; i < files.length; i++) {
            const file = files[i],
                reader = new FileReader();

            console.log(file);

            reader.onload = function (e) {
                const item = document.createElement("div"),
                    img = document.createElement("img"),
                    btn = document.createElement("div");

                item.classList.add("pic");
                btn.classList.add("del");

                img.src = e.target.result;

                item.appendChild(img);
                item.appendChild(btn);

                preview[0].appendChild(item);
            };

            reader.readAsDataURL(file);
        }
    });

    $('.product-review').on('click', '.del', function (event) {
        var input = $(this).closest('.form-group').find('input[type="file"]'),
            item = $(this).closest('.pic'),
            img = item.children('img'),
            files = new DataTransfer();

        for (var i = 0; i < input[0].files.length; i++)
        {
            const file = input[0].files[i],
                reader = new FileReader();

            reader.onload = function (e) {
                if (e.target.result !== img[0].src) {
                    files.items.add(file);
                }
            };

            reader.readAsDataURL(file);
        }

        input[0].files = files.files;
        item.remove();
    });
});