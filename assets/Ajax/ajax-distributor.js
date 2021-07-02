$(document).ready(function(e) {
    $(document).on('click', '#distributor-form', function(e) {
        // alert("OK");
        e.preventDefault();
        $.ajax({
            type: 'POST',
            url: 'AjaxFunction/AjaxDistributor.php',
            data: $('#form-data').serializeArray(),
            dataType: 'json',
            beforeSend: function(x) {
                // $('#status').show();
                // $('#preloader').show();
            }
        }).done(function(response) {
            $('#status').hide();
            $('#preloader').hide();
            if (response['status'] == true)
            {
                $('#o-message').removeClass('').addClass('alert alert-success');
                $('#o-message').html(response['message']);
                if (response['type'] == 1)
                {
                    $('#form-data')[0].reset();
                    $("#state_name").val('').trigger('change');
                    $("#city_name").val('').trigger('change');
                }
                alertHelper();
            }
            else
            {
                $('#o-message').empty();
                $('#o-message').html(response['message']);
                $('#o-message').removeClass('').addClass('alert alert-danger');
                alertHelper();
            }
        });
    });
    $(document).on('click', '.delete-customer', function() {
        // alert('123');
        var id = $(this).data('id');
        $.ajax({
            type: 'POST',
            url: 'AjaxFunction/DeleteFunction.php',
            data: {
                'id': id,
                'method': 'delete',
                'target': 'customer'
            },
            dataType: 'JSON',
            beforeSend: function(x) {
                $('#status').show();
                $('#preloader').show();
            }
        }).done(function(response) {
            $('#status').hide();
            $('#preloader').hide();
            $('.loading').css('visibility', 'hidden');
            if (response['status'] == true) {
                $('.successCls').removeClass('hide');
                $('.successCls').addClass('show');
                $('.errorCls').addClass('hide');
                $('.successmsg').html(response['message']);
                var search = $('#search').val();
                var sorts = $('.table .show').parent('th').data('sort');
                var order = $('#status').val();
                loadInCartitems(1, sorts, order, search);
                alertHelper();
            } else {
                $('.errorCls').removeClass('hide');
                $('.errorCls').addClass('show');
                $('.successCls').addClass('hide');
                $('.errormsg').html(response['message']);
                alertHelper();
            }
        });
    });

    function alertHelper() {
        setTimeout(function() {
            $('#o-message').empty();
            $('#o-message').html();
            $('#o-message').removeClass();

            $('.successCls').removeClass('show').addClass('hide');
            $('.errorCls').removeClass('show').addClass('hide');
        }, 5000);
    }

    function gototop() {
        $('html, body').animate({
            scrollTop: 0
        }, 1000);
    }

    function loadInCartitems(page, sort, order, search) {
        var npage,
            nsort;
        if (page) {
            npage = page;
        } else {
            npage = 1;
        }
        if (sort) {
            nsort = sort;
        } else {
            nsort = 0;
        }
        $.ajax({
            method: 'POST',
            data: {
                'page': npage,
                'sort': nsort,
                'order': order,
                'search': search
            },
            url: 'inc/manage-employee.php',
            beforeSend: function(x) {
                $('#status').show();
                $('#preloader').show();
            }
        }).done(function(response) {
            $('#status').hide();
            $('#preloader').hide();
            if (response == 1) {
                $('#error').removeClass('hide');
                $('.table').addClass('hide');
            } else {
                $('#error').addClass('hide');
                $('.table').removeClass('hide');
                $('#getCategory').html(response);
            }
        });
    }
    $(document).on('click', '.sorting', function() {
        var sort = $(this).data('sort');
        var status = $('#status').val();
        if (status == 0) {
            $('#status').val(1);
        } else {
            $('#status').val(0);
        }
        var search = $('#search').val();
        var order = $('#status').val();
        loadInCartitems(0, sort, order, search);
        $('.sorting i').removeClass('show');
        $(this).find('i').addClass('show');
    });
    $(document).on('click', '.pages', function() {
        var search = $('#search').val();
        var page = $(this).data('page');
        var sorts = $('.table .show').parent('th').data('sort');
        var order = $('#status').val();
        loadInCartitems(page, sorts, order, search);
        gototop();
    });
    $(document).on('click', '#dSuggest', function() {
        var search = $('#search').val();
        var sorts = $('.table .show').parent('th').data('sort');
        var order = $('#status').val();
        loadInCartitems(1, sorts, order, search);
    });
    var search = $('#search').val();
    var sorts = $('.table .show').parent('th').data('sort');
    var order = $('#status').val();
    loadInCartitems(1, sorts, order, search);
});