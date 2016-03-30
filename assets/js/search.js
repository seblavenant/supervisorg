var Searcher = (function($) {

    Searcher = function() {
        this.$searchInput = $('#process-search');
        this.$searchSubmitButton = $('#process-search-btn');
    };

    Searcher.prototype.init = function() {
        this.bindEvents();
    };

    Searcher.prototype.bindEvents = function() {
        var self = this;

        Mousetrap.bind('f', function() {
            self.$searchInput.focus();
            return false;
        });

        self.$searchInput.on('keyup', function() {
            var searchedValue = $(this).val().toLowerCase();
            self.search(searchedValue);
        });

        self.$searchInput.on('keyup', function(e) {
            self.search();
            e.preventDefault();
        });

        self.$searchSubmitButton.on('click', function(e) {
            self.search();

            e.preventDefault();
        })
    };

    Searcher.prototype.search = function() {
        var searchedValue = this.$searchInput.val().toLowerCase();

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

            var isNameMatching = (processName.indexOf(searchedValue) > -1);
            var isStateNameMatching = (stateName.indexOf(searchedValue) > -1);

            $(this).show();
            if( ! isNameMatching && ! isStateNameMatching )
            {
                $(this).hide();
            }
        });
    };

    return Searcher;
})(jQuery);

var searcher = new Searcher();
searcher.init();
