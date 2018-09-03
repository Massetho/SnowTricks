$(document).ready(function(){
    $('body').on('click', '.more_tricks', function (e){
        e.preventDefault();
        var ID = $(".trick-vignette:last-child").attr("data-trick-id");
        var loaderGif = $(this).attr("data-loader-gif");
        var path = $(this).attr("data-path");
        if(ID) {
            $.ajax({
                type: 'GET',
                url: path,
                data: { last_id: ID },
                beforeSend:function(){
                    $(".more_tricks").hide();
                    $("#more").append(loaderGif);
                },
                success: function (response) {

                    $('#tricks-thumbs').append(response);

                    $("#more img").remove();
                    $(".more_tricks").show();
                }
            });
        } else {
            $(".morebox").html('No more tricks.');//
        }
    });
});