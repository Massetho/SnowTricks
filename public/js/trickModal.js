$(document).on("click", '.openModal', function(event) {
        var dataURL = $(this).attr('data-href');
        $('.trick-modal-body').empty();
        $('.trick-modal-body').load(dataURL);
        $('#trickModal').modal('show');
    });