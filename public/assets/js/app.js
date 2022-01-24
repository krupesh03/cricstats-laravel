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
});