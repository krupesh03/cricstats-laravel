$(document).ready( function() {

    $(document).on('click', '.team-format', function() {

        var $this = $(this);
        if( $('.team-format').not($this).hasClass('color-switch-active') ) {
            $('.team-format').not($this).removeClass('color-switch-active');
            $('.team-format').not($this).addClass('color-switch-inactive');
        }
        $this.addClass('color-switch-active').removeClass('color-switch-inactive');
        $('.format-rankings').find('.table').hide();
        $this.parent('.format-rankings').find('.table').show();
    });

    $(document).on('click', '.league-info-url', function() {
        
        var $this = $(this);
        var leagueId = $this.data('pid');
        if( leagueId ) {
            document.location.href = '/seasons/' + leagueId;
        }
    });

    $(document).on('click', '.season-name', function() {

        var $this = $(this);
        var seasonId = $this.data('pid');
        var currentUrl = $this.data('current-url');
        if( seasonId && currentUrl ) {
            document.location.href = currentUrl + '/teams/' + seasonId;
        }
    });

    $(document).on('click', '.teams-list', function() {

        var $this = $(this);
        var teamId = $this.data('pid');
        var seasonId = $this.data('id');
        var currentUrl = $this.data('current-url');
        if( teamId && seasonId && currentUrl ) {
            document.location.href = currentUrl + '/squads/' + teamId + '/season/' + seasonId;
        }
    });
});