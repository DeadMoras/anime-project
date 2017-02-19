$(document).ready(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="token"]').attr('content')
        }
    });

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

    $('.chips').material_chip();

    $('.chips').on('chip.add', function(e, chip){
        $.ajax({
            method: 'post',
            url: '/admin/genre/add',
            dataType: 'json',
            data: {
                chip: chip.tag,
                bundle: $("input[name='genre_bundle']").val()
            },
            success: function(data) {
                $('form').appendTo('<input type="hidden" name="genres['+ data.success.id +']" value="1">');
            }
        });
    });

});