
/**
 *
 * @type {{init: FileUpload.init, stop: FileUpload.stop, start: FileUpload.start, progress: FileUpload.progress, params: {}, done: FileUpload.done}}
 */
var FileUpload = {
    params: {},
    init: function(params) {
        this.params = params;

        var preview = document.getElementById("uploaded_files");

        $(preview).sortable();
    },
    done: function(e, data) {

        var input = e.target,
            files = data.result.files,
            preview = document.getElementById("uploaded_files");

        for (var i = 0; i < files.length; i++) {

            var tr = document.createElement("tr"),
                td0 = document.createElement('td'),
                td1 = document.createElement('td'),
                td2 = document.createElement('td'),
                td3 = document.createElement('td'),
                img = document.createElement("img"),
                fileUuid = document.createElement('input'),
                filePosition = document.createElement('input'),
                btn = document.createElement('a');

            fileUuid.type = 'hidden';
            fileUuid.name = FileUpload.params.namespace + '[uuid]';
            fileUuid.value = files[i].uuid;

            img.classList.add('img-thumbnail');
            img.width = 90;
            img.height = 90;
            img.src = files[i].thumbnailUrl;

            btn.href = files[i].deleteUrl;
            btn.innerText = 'Delete';
            btn.classList.add('btn');
            btn.classList.add('btn-danger');

            td0.appendChild(img);
            td1.innerText = files[i].name;
            td1.appendChild(fileUuid);
            td2.innerText = files[i].size;
            td3.appendChild(btn);

            tr.appendChild(td0);
            tr.appendChild(td1);
            tr.appendChild(td2);
            tr.appendChild(td3);

            preview.appendChild(tr);
        }

        $(preview).removeClass('refresh');
        $(preview).sortable();

        FileUpload.stop(e);
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
            preview = document.getElementById("uploaded_files");

        form.addClass('loading');
        form.find('.progress-bar')
            .addClass('active')
            .removeClass('hidden');

        if (!$(preview).hasClass('refresh')) {
            $(preview).html('')
                .addClass('refresh');
        }
    },
    stop: function(e) {
        $(e.target.form).removeClass('loading');

        $(e.target.form)
            .find('.progress-bar')
            .css('width', 0)
            .prop('aria-valuenow', 0)
            .removeClass('active')
            .addClass('hidden');
    }
};