/*!
* Start Bootstrap - Scrolling Nav v4.3.0 (https://startbootstrap.com/template/scrolling-nav)
* Copyright 2013-2021 Start Bootstrap
* Licensed under MIT (https://github.com/StartBootstrap/startbootstrap-scrolling-nav/blob/master/LICENSE)
*/
$(document).ready(function () {
    $(document).on("submit", "#create_stock", function(e){
        e.preventDefault();
        return  false;
    });
    console.log('ffffffffffffffffff');
    $('.edit_btn').click(function () {
        const itemId = $(this).attr('data-id');
        const itemSymbol = $(this).attr('data-symbol');
        $('#stock_edit_id').attr('value',itemId);
        $('#stock_edit_symbol').attr('value',itemSymbol);
        console.log(itemId);
    });
    $('#cancel_search').click(function () {
        window.location.replace( $('#urls').attr('data-index'));
    });
    $('.delete_btn').click(function () {
        const itemId = $(this).attr('data-id');
        const url = $(this).attr('data-url');
        $('#item_delete_id').attr('value',itemId);
        $('#item_delete_url').attr('value',url);
        console.log(itemId);
    });

    $('#delete_item_submit').click(function () {
        $(this).attr("disabled",false);
        let form = $("#deleteItemForm");
        var form_data = form.serialize();
        $.ajax({
            url: $('#item_delete_url').val(),
            type: 'POST',
            dataType: 'json',
            data: form_data,
            success:function(data){
                // handling the response data from the controller
                if(!data.success){
                    $('#stockDeleteErrors').html(data.msg).show();
                    console.log("[API] ERROR: "+data.msg);
                }
                if(data.success){
                   window.location.reload();
                }
                // signal to user the action is done
                $('#loadingSpinner').hide();
                $('#delete_item_submit').attr("disabled",false);
            }
        });
    });
    $('#stock_update_submit').click(function () {
        $(this).attr("disabled",false);
        let form = $("#updateStockForm");
        if(!form[0].checkValidity()){
            $('.symbol_required_error').show();
            return false;
        }
        var form_data = form.serialize();

        $.ajax({
            url: $('#urls').attr('data-update'),
            type: 'PUT',
            dataType: 'json',
            data: form_data,
            success:function(data){
                // handling the response data from the controller
                if(!data.success){
                    $('#stockUpdateErrors').html(data.msg).show();
                    console.log("[API] ERROR: "+data.msg);
                }
                if(data.success){
                   window.location.reload();
                }
                // signal to user the action is done
                $('#loadingSpinner').hide();
                $('#stock_update_submit').attr("disabled",false);
            }
        });
    });
    $('#stock_submit').click(function () {
        $(this).attr("disabled",false);
        let form = $("#create_stock");
        if(!form[0].checkValidity()){
            $('.symbol_required_error').show();
            return false;
        }
        var form_data = form.serialize();

        $.ajax({
            url: $('#urls').attr('data-save'),
            type: 'POST',
            dataType: 'json',
            data: form_data,
            success:function(data){

                // handling the response data from the controller
                if(!data.success){
                    $('#stockErrors').html(data.msg).show();
                    console.log("[API] ERROR: "+data.msg);
                }
                if(data.success){
                   window.location.reload();
                }

                // signal to user the action is done
                $('#loadingSpinner').hide();
                $('#stock_submit').attr("disabled",false);
            }
        });
    });
});


