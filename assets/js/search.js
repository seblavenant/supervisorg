$('#process-search').on('keyup', function() {
    var searchedValue = $(this).val();

    if(searchedValue == '')
    {
        $('.process-tile').each(function(index, $item) {
            $(this).show();
        });

        return;
    }

    $('.process-tile').each(function(index, $item) {
        if(! ($(this).data('process-name').indexOf(searchedValue) > -1) )
        {
            $(this).hide()
        }
    });
});
