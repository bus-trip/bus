$(document).ready(function () {
    $('.select').styler();
    $('.input-checkbox').styler();
    
    if($('.form-table_ticket').length) {
        if($('.form-table_ticket').length > 2 && $(window).width()>992) {
            $('.form-table_ticket').parent().css({'width': '33.3333%'})
        }
    }
});

$(window).on('resize', function() {
    if($('.form-table_ticket').length > 2 && $(window).width()>992) {
        $('.form-table_ticket').parent().css({'width': '33.3333%'});
    } else {
        $('.form-table_ticket').parent().css({'width': ''});
    }
});