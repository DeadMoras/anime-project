(function ($) {

    $(document).ready(function () {
        $('ul.tabs').tabs();
        $('.parallax').parallax();
        $('.modal').modal();

        $('.dropdown-button').dropdown({
                    inDuration: 300,
                    outDuration: 225,
                    constrain_width: false, // Does not change width of dropdown to that of the activator
                    hover: false, // Activate on hover
                    gutter: 100, // Spacing from edge
                    belowOrigin: false, // Displays dropdown below the button
                    alignment: 'right' // Displays dropdown with edge aligned to the left of button
                }
        );

        // $(document).scroll(function() {
        //  var $scroll = $(this).scrollTop();
        //  var $topDist = $('body').position();
        //  if ( $scroll> $topDist.top ) {
        //      $('header').addClass('fixed-header');
        //  } else {
        //      $('header').removeClass('fixed-header');
        //  }
        // });

    });

})(jQuery);
