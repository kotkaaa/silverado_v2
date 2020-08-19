
var FileUpload = {
    done: function(e, data) {
        var input = e.target,
            files = data.result.files,
            preview = $(e.target.form).find(".preview-images");

        for (let i = 0; i < files.length; i++) {

            var div = document.createElement("div"),
                img = document.createElement("img"),
                file = document.createElement('input'),
                span = document.createElement("span"),
                btn = document.createElement('button');

            file.type = 'hidden';
            file.name = input.name;
            file.value = files[i].name;

            div.classList.add("item");
            img.classList.add("photo");
            img.src = getUploadedFilePreview(files[i].name, files[i].thumbnailUrl);
            span.classList.add("name");
            span.innerText = files[i].name;
            btn.classList.add('glyphicon');
            btn.classList.add('glyphicon-remove');
            btn.classList.add('btn__del');
            btn.onclick = function(e) {
                e.preventDefault();
                $(e.target).closest('.item').remove();
                return false;
            }

            $(div).append(file).append(img).append(span).append(btn);
            preview.append(div);
        }
    },
    progress: function(e, data) {
        var progress = parseInt(data.loaded / data.total * 100, 10);
        $(e.target.form)
            .find('.progress-bar')
            .css('width', progress + '%')
            .prop('aria-valuenow', progress);
    },
    start: function(e) {
        var form = $(e.target.form),
            preview = form.find('.preview-images');
        form.addClass('loading');
        if (!preview.hasClass('refresh')) {
            preview.html('').addClass('refresh');
        }
    },
    stop: function(e) {
        $(e.target.form).removeClass('loading');
        $(e.target.form)
            .find('.progress-bar')
            .css('width', 0)
            .prop('aria-valuenow', 0);
    }
};