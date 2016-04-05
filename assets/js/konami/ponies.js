$(document).on('Konami', function() {
    var url = 'http://placeponi.es/300/200/',
        imageIndex = 1;

    $('.process-tile .small-box').each(function(index, item) {
        img = url + imageIndex.toString();

        $(item).addClass("inverted").css({
            'background-image': "url('" + img + "')",
            'background-position': "50% 50%",
            'background-size': "cover",
            'color': "#000",
        });

        imageIndex++;

        if( imageIndex >= 10 )
        {
            imageIndex = 1;
        }

    });
});
