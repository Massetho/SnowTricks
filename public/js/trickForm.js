// setup an "add a Image" link
var $addImageLink = $('<a href="#" class="btn btn-dark add_Image_link">Add a Image</a>');
var $newLinkLi = $('.images-button-box').append($addImageLink);

// setup an "add a Video" link
var $addVideoLink = $('<a href="#" class="btn btn-dark add_Video_link">Add a Video</a>');
var $newLinkLiV = $('.videos-button-box').append($addVideoLink);

jQuery(document).ready(function() {
    // Get the ul that holds the collection of Images
    var $collectionImageHolder = $('.images');
    var $addImageBox = $('.images-box');

    // add the "add a Image" anchor and li to the Images ul
    //$collectionImageHolder.append($newLinkLi);
    $addImageBox.append($newLinkLi);

    // count the current form inputs we have (e.g. 2), use that as the new
    // index when inserting a new item (e.g. 2)
    $collectionImageHolder.data('index', $collectionImageHolder.find(':input').length);

    $addImageLink.on('click', function(e) {
        // prevent the link from creating a "#" on the URL
        e.preventDefault();

        // add a new Image form (see code block below)
        addElementForm($collectionImageHolder, $newLinkLi);
    });

    // Get the ul that holds the collection of Video
    var $collectionVideoHolder = $('.videos');
    var $addVideoBox = $('.videos-box');

    // add the "add a Image" anchor and li to the Images ul
    //$collectionVideoHolder.append($newLinkLiV);
    $addVideoBox.append($newLinkLiV);

    // count the current form inputs we have (e.g. 2), use that as the new
    // index when inserting a new item (e.g. 2)
    $collectionVideoHolder.data('index', $collectionVideoHolder.find(':input').length);

    $addVideoLink.on('click', function(e) {
        // prevent the link from creating a "#" on the URL
        e.preventDefault();

        // add a new Image form (see code block below)
        addElementForm($collectionVideoHolder, $newLinkLiV);
    });
});



function addElementForm($collectionHolder, $newLinkLi) {
    // Get the data-prototype explained earlier
    var prototype = $collectionHolder.data('prototype');

    // get the new index
    var index = $collectionHolder.data('index');

    // Replace '$$name$$' in the prototype's HTML to
    // instead be a number based on how many items we have
    var newForm = prototype.replace(/__name__/g, index);

    // increase the index with one for the next item
    $collectionHolder.data('index', index + 1);

    // Display the form in the page in an li, before the "Add a Image" link li
    var $newFormLi = $('<div class="col-12 flex-container"></div>').append(newForm);

    // also add a remove button, just for this example
    $newFormLi.append('<a href="#" class="remove-Image"><span class="oi oi-x"></span></a>');

    $newLinkLi.before($newFormLi);

    // handle the removal, just for this example
    $('.remove-Image').click(function(e) {
        e.preventDefault();

        $(this).parent().remove();

        return false;
    });
}
