$(document).ready(function () {
  function gototop() {
    $('html, body').animate({
      scrollTop: 0
    }, 1000);
  }
  function loadInCartitems(page, sort, order, search) {
    var npage,
    nsort;
    if (page)
    {
      npage = page;
    } 
    else
    {
      npage = 1;
    }
    if (sort)
    {
      nsort = sort;
    } 
    else
    {
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
      url: 'inc/manage-store.php',
      beforeSend: function (x) {
        $('.loading').css('visibility', 'visible');
      }
    }).done(function (response) {
      $('.loading').css('visibility', 'hidden');
      if (response == 1)
      {
        $('#error').removeClass('hide');
        $('.table').addClass('hide');
      } 
      else
      {
        $('#error').addClass('hide');
        $('.table').removeClass('hide');
        $('#getCategory').html(response);
      }
    });
  }
  $(document).on('click', '.sorting', function () {
    var sort = $(this).data('sort');
    var status = $('#status').val();
    if (status == 0)
    {
      $('#status').val(1);
    } 
    else
    {
      $('#status').val(0);
    }
    var search = $('#search').val();
    var order = $('#status').val();
    loadInCartitems(0, sort, order, search);
    $('.sorting i').removeClass('show');
    $(this).find('i').addClass('show');
  });
  $(document).on('click', '.pages', function () {
    var search = $('#search').val();
    var page = $(this).data('page');
    var sorts = $('.table .show').parent('th').data('sort');
    var order = $('#status').val();
    loadInCartitems(page, sorts, order, search);
    gototop();
  });
  $(document).on('click', '#dSuggest', function () {
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
function alertHelper()
{
  setTimeout(function ()
  {
    $('.successCls').removeClass('show').addClass('hide');
    $('.errorCls').removeClass('show').addClass('hide');
  }, 2000);
}
$(document).on('click', '.delete-btn', function () {
  //alert('dshgfdhg');
  $(this).parent().parent().remove();
  var id = $(this).data('id');
  $.ajax({
    type: 'POST',
    url: 'AjaxFunction/DeleteFunction.php',
    data: {
      'id': id,
      'method': 'delete',
      'target': 'employee'
    },
    dataType: 'json',
  }).done(function (response)
  {
    if (response['status'] == true)
    {
      $('.successCls').removeClass('hide');
      $('.successCls').addClass('show');
      $('.errorCls').addClass('hide');
      $('.successmsg').html(response['message']);
      alertHelper();
    } 
    else
    {
      $('.errorCls').removeClass('hide');
      $('.errorCls').addClass('show');
      $('.successCls').addClass('hide');
      $('.errormsg').html(response['message']);
      alertHelper();
    }
  });
});
$(document).on('click', '.customer_view', function () {
  var id = $(this).data('id');
  $.ajax({
    type: 'POST',
    url: 'inc/customer-model-view.php',
    data: {
      'id': id,
      'method': 'view'
    },
    dataType: 'json',
  }).done(function (response)
  {
    if (response['status'] == 'true')
    {
      $('.modal-body').html(response['message']);
    } 
    else
    {
      $('.errorCls').removeClass('hide');
      $('.errorCls').addClass('show');
      $('.errormsg').html('Oops ! Can\'t View');
      alertHelper();
    }
  });
});
$('#customer_view').on('hidden.bs.model', function () {
  alert('sdhjhg');
});