$(document).ready(function(){
    $('body').on('click', '.more_comments', function (e){
        e.preventDefault();
        var ID = $(this).attr("id");
        var loaderGif = $(this).attr("data-loader-gif");
        var path = $(this).attr("data-path");
        var trickID = $(this).attr("data-trick-id");
        if(ID !== "0") {
            $("#moreComments"+ID).html(loaderGif);

            $.ajax({
                type: 'POST',
                url: path,
                data: { last_id: ID, trick_id: trickID },
                success: function (response) {
                    $('.comments').append(response);
                    $("#moreComments"+ID).remove();
                }
            });
        }
        return false;
    });
});