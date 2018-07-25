$('.modal').on('click', '[data-dismiss="modal"]', function(e) {
    e.stopPropagation();
});

$('.modal').on('hidden.bs.modal', function (e) {
    $('body').addClass('modal-open');
});