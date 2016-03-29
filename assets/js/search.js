$searchInput = $('#process-search');

Mousetrap.bind('f', function() {
    $searchInput.focus();
    return false;
});

$searchInput.on('keyup', function() {
    var searchedValue = $(this).val().toLowerCase();

    if(searchedValue == '')
    {
        $('.process-tile').each(function(index, $item) {
            $(this).show();
        });

        return;
    }

    $('.process-tile').each(function(index, $item) {
        var processName = $(this).data('process-name').toLowerCase();
        var stateName = $(this).data('process-state-name').toLowerCase();

        var isNameMatching = (processName.indexOf(searchedValue) > -1)
        var isStateNameMatching = (stateName.indexOf(searchedValue) > -1)

        if( ! isNameMatching && ! isStateNameMatching )
        {
            $(this).hide()
        }
    });
});
