$(document).ready( function() {

    $(document).on('click', '.team-format', function() {

        var $this = $(this);
        if( $('.team-format').not($this).hasClass('color-switch-active') ) {
            $('.team-format').not($this).removeClass('color-switch-active');
            $('.team-format').not($this).addClass('color-switch-inactive');
        }
        var id = $this.attr('id');
        $this.addClass('color-switch-active').removeClass('color-switch-inactive');
        $('.format-rankings').find('.table').hide();
        $('.format-rankings').find('#'+id).show();
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

    $(document).on('click', '.single-team-info', function() {

        var $this = $(this);
        var teamId = $this.data('pid');
        var seasonId = $this.data('id');
        var currentUrl = $this.data('current-url');
        if( teamId && seasonId && currentUrl ) {
            document.location.href = currentUrl + '/squads/' + teamId + '/season/' + seasonId + '/home';
        }
    });

    $(document).on('click', '.fixture-list-url', function() {

        var $this = $(this);
        var fixtureId = $this.data('pid');
        var currentUrl = $this.data('current-url');
        if( fixtureId && currentUrl ) {
            document.location.href = currentUrl + '/fixture/' + fixtureId;
        }
    });

    $(document).on('click', '.league-info-url-fixture', function() {

        var $this = $(this);
        var leagueId = $this.data('pid');
        var seasonId = $this.data('id');
        if( leagueId && seasonId ) {
            document.location.href = '/fixture/' + seasonId + '/season/' + leagueId;
        }
    });

    $(document).on('click', '.live-score-url', function() {

        var $this = $(this);
        var fixtureId = $this.data('pid');
        var currentUrl = $this.data('current-url');
        if( fixtureId && currentUrl ) {
            document.location.href = currentUrl + '/livescores/' + fixtureId + '/score';
        }
    });

    //reload scorecard for live scores
    if( $('.team-allLiveScores').length != 0 ) {
        setTimeout(function () {
            location.reload(true);
        }, 30000);
    }

    if( $('.match-in-progress').length != 0 ) {
        setTimeout(function () {
            location.reload(true);
        }, 30000);
    }
    
    //change title of pages
    if( $('.heading').length != 0 && $('.heading').text() != '' ) {
        pageTitle = '';
        if( $('.innings-progress-score').length != 0 && $('.innings-progress-score').text() != '' ) {
            pageTitle = $('.innings-progress-score').text();
        }
        newPageTitle = $('.heading').text();
        document.title = $('title').text() +' | '+ pageTitle + newPageTitle;
    }

    if( $('.live-scorecard').length != 0 ) {
        if( localStorage.getItem('fullLoad') === null ) {
            localStorage.setItem('fullLoad', 'false');
        }
    } else {
        localStorage.removeItem('fullLoad');
    }
    if( $('.live-commentary').length != 0 ) {
        if( localStorage.getItem('fullLoad') == 'false' && $('.live-commentary').find('table tr').length > 70 ) {
            $('.live-commentary').find('table tr:gt(70)').hide();
            $('.load-more-commentary').show();
        }
    }

    $(document).on('click', '.load-more-commentary', function() {
        
        $(this).hide();
        $('.live-commentary').find('table tr:gt(70)').show();
        localStorage.setItem('fullLoad', 'true');
    });

    $(document).on('click', 'input[type=reset]', function(e) {

        e.preventDefault();
        var $this = $(this);
        $this.closest('form').find(':input').each( function() {
            $(this).not('[type=reset],[type=submit]').val('');
        });
        $this.closest('form').submit();
    });

    if( $('.featured-matches').length != 0 ) {
        setTimeout(function () {
            location.reload(true);
        }, 30000);
    }

    $(document).on('click', '.squad-player-url', function() {

        var $this = $(this);
        var playerId = $this.data('pid');
        var currentUrl = $this.data('current-url');
        if( playerId && currentUrl ) {
            document.location.href = currentUrl + '/players/' + playerId;
        }
    });
});