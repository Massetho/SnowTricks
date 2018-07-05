$(document).ready(function(){
    $('body').on('click', '.more_tricks', function (){
        var ID = $(this).attr("id");
        var loaderGif = $(this).attr("data-loader-gif");
        var path = $(this).attr("data-path");
        if(ID) {
            $("#more"+ID).html(loaderGif);

            $.ajax({
                type: 'GET',
                url: path,
                data: { last_id: ID },
                success: function (response) {

                    $('#tricks-thumbs').append(response);
                    $("#more"+ID).remove();
                }
            });
        } else {
            $(".morebox").html('The End');// no results
        }

        return false;
    });
});