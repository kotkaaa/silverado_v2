
$(document).on('click', '.main-modal-trigger', function (e) {

    e.preventDefault();

    var header = $(this).data('header-text') || '',
        content = $(this).next('.modal-content').html();

    $('#main-modal').find('.modal-header').children('span').text(header);
    $('#main-modal').find('.modal-body').html(content);
});

$('#main-modal').on('hidden.bs.modal', function (e) {
    $(this).find('.modal-header span').text('');
    $(this).find('.modal-body').html('');
});