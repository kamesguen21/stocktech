/*!
* Start Bootstrap - Scrolling Nav v4.3.0 (https://startbootstrap.com/template/scrolling-nav)
* Copyright 2013-2021 Start Bootstrap
* Licensed under MIT (https://github.com/StartBootstrap/startbootstrap-scrolling-nav/blob/master/LICENSE)
*/
$(document).ready(function () {
console.log('descripppppppp');
    $('#description_stock').change(function () {
        const val = $("#description_stock option:selected").text();
        $('#description_symbol').attr('value', val);
        console.log("val" + $("#description_stock option:selected").text());
    });
    $('#cancel_search').click(function () {
        window.location.replace($('#urls').attr('data-index'));
    });
    $('.delete_btn').click(function () {
        $('#deleteItemForm').attr('action', $(this).attr('data-url'));
    });
});


