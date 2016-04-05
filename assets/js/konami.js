$(document).ready(function() {
    Mousetrap.bind('up up down down left right left right b a', function() {
        $(document).trigger('Konami');
    });
});
