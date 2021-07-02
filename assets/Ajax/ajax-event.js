$(document).ready(function (e) {
  $('#event-form').on('submit', function (e) {
    e.preventDefault();
    $.ajax({
      type: 'POST',
      url: 'AjaxFunction/AjaxEvent.php',
      data: new FormData(this),
      contentType: false,
      cache: false,
      processData: false,
      dataType: 'json',
      beforeSend: function (x) {
        $('#status').show();
        $('#preloader').show();
      }
    }).done(function (response)
    {
      $('#status').hide();
      $('#preloader').hide();
      if (response['status'] == true)
      {
        $('.successCls').removeClass('hide');
        $('.successCls').addClass('show');
        $('.errorCls').addClass('hide');
        $('.successmsg').html(response['message']);
        $('#event-form')[0].reset();
        $('.img-upload2').attr('src', 'images/document-image.png');
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
  //Edit Category
  $('#eate-form').on('submit', function (e) {
    e.preventDefault();
    $.ajax({
        type: 'POST',
        url: 'AjaxFunction/AjaxEvent.php',
        data: new FormData(this),
        contentType: false,
        cache: false,
        processData: false,
        dataType: 'json',
        beforeSend: function (x) {
          $('#status').show();
          $('#preloader').show();
        }
    }).done(function (response)
    {
      $('#status').hide();
      $('#preloader').hide();
      if (response['status'] == true)
      {
        $('.successCls').removeClass('hide');
        $('.successCls').addClass('show');
        $('.errorCls').addClass('hide');
        $('.successmsg').html(response['message']);
        $('#cate-form') [0].reset();
        $('.img-upload2').attr('src', 'images/document-image.png');
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
  $(document).on('click', '.delete-event', function () {
    var cateid = $(this).data('id');
    $.ajax({
      type: 'POST',
      url: 'AjaxFunction/DeleteFunction.php',
      data: {
        'id': cateid,
        'method': 'delete',
        'target': 'event'
      },
      dataType: 'JSON',
      beforeSend: function (x) {
        $('#status').show();
        $('#preloader').show();
      }
    }).done(function (response)
    {
        $('#status').hide();
      $('#preloader').hide();
      $('.loading').css('visibility', 'hidden');
      if (response['status'] == true)
      {
        $('.successCls').removeClass('hide');
        $('.successCls').addClass('show');
        $('.errorCls').addClass('hide');
        $('.successmsg').html(response['message']);
        var search = $('#search').val();
        var sorts = $('.table .show').parent('th').data('sort');
        var order = $('#status').val();
        loadInCartitems(1, sorts, order, search);
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
  function alertHelper()
  {
    setTimeout(function ()
    {
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
      url: 'inc/manage-event.php',
      beforeSend: function (x) {
        $('#status').show();
        $('#preloader').show();
      }
    }).done(function (response) {
      $('#status').hide();
      $('#preloader').hide();
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

  $('.file_input').click(function () {
      var id = $(this).attr('data-id');
      $('#file-input' + id).change(function () {
        if (this.files && this.files[0]) {
          var reader = new FileReader();
          reader.onload = function (e) {
            $('.img-upload' + id).attr('src', e.target.result);
          };
          reader.readAsDataURL(this.files[0]);
        }
      });
    });
  
  
});