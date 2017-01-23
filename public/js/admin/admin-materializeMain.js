$(document).ready(function() {
    $('select').material_select();
    $('ul.tabs').tabs();

    $('body').on('change', '#items-list-action', function() {
        var itemsList = $('#items-list');
        itemsList.find('input[name="action"]').val($(this).val());
    });

    $('body').on('click', '#items-list-submit', function(event) {
        event.preventDefault();
        var itemsList = $('#items-list');
        itemsList.submit();
    });

    (function() {
        var $spanActivePagination = $('.pagination .active span').text();
        $('.pagination .active span').remove();
        $('.pagination .active').append('<a href="">' + $spanActivePagination + '</a>');

        if( !$('.pagination li').hasClass('active') ) {
            $('.pagination li').addClass('waves-effect');
        }
    })();

});