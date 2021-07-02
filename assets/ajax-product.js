$(document).ready(function (e) {
  $('.popover-content').hide();
  //itemvalidate();
  var page_load_pid = $('.pdtid').val();
  if (page_load_pid)
  {
    $('.menus').removeClass('disabled');
    $('.pdt-continue1').removeClass('disabled');
    $('.pdt-continue2').removeClass('disabled');
    $('.pdt-continue3').removeClass('disabled');
    $('.pdt-continue4').removeClass('disabled');
    $('.pdt-continue5').removeClass('disabled');
  }
  function itemvalidate()
  {
    var pdtname = $('.pdt-name').val();
    // var barname = $('.brand-name').val();
    var catname = $('.cate-name').val();
    // var variety_ty = $('.variety').val();
    var price = $('.oprice').val();
    var mi_qty = $('.mi-qty').val();
    var ma_qty = $('.ma-qty').val();
    // var time = $('.time').val();
    var desc = $('.desc').val();
    var specifi = $('.specifi').val();
    
    if (pdtname && catname && price && mi_qty && ma_qty && desc && specifi)
    {
      $('.menus').removeClass('disabled');
    }
  }  //product details

  $('.pdt-name').keyup(function () {
    var pdtval = $(this).val();
    if (pdtval == '')
    {
      $('.pdt-continue1').addClass('disabled');
      $('.menus').addClass('disabled');
    } 
    else
    {
      $('.pdt-continue1').removeClass('disabled');
      itemvalidate();
    }
  });
  $('.pdt-continue1').on('click', function () {
    $('.cate-tab').removeClass('disabled');
    $('.nav-link').removeClass('active');
    $('.tab-pane').removeClass('active');
    $('.tab-pane').removeClass('show');
    $('.cate-tab').addClass('active');
    $('.cate-tab').addClass('show');
  });
  //category details
  
  $('.cate-name').on('change', function () {
    var cateval = $(this).val();
    if (cateval == '')
    {
      $('.pdt-continue2').addClass('disabled');
      $('.menus').addClass('disabled');
      $('.active').removeClass('disabled');
    } 
    else
    {
      $('.pdt-continue2').removeClass('disabled');
      itemvalidate();
    }
  });
  $('.pdt-continue2').on('click', function () {
    $('.price-tab').removeClass('disabled');
    $('.nav-link').removeClass('active');
    $('.tab-pane').removeClass('active');
    $('.tab-pane').removeClass('show');
    $('.price-tab').addClass('active');
    $('.price-tab').addClass('show');
  });

  $('.mrp,.mi-qty,.ma-qty').keyup(function () {
    var price = $('.oprice').val();
    var mi_qty = $('.mi-qty').val();
    var ma_qty = $('.ma-qty').val();
    if (/\D/g.test(this.value))
    {
      this.value = this.value.replace(/\D/g, '');
      $('.errormsg').removeClass('hide');
      $('.errormsg').html('<strong>Only Integer Value In Required Field !');
      alertHelper();
    } 
    else
    {
      $('.errormsg').addClass('hide')
    }
    if(price && mi_qty && ma_qty)
    {
      console.log();
      $('.pdt-continue3').removeClass('disabled');
      itemvalidate();
    } 
    else
    {
      $('.pdt-continue3').addClass('disabled');
      $('.spci-tab').addClass('disabled');
      $('.img-tab').addClass('disabled');
      $('.active').removeClass('disabled');
    }
  });

  $('.pdt-continue3').on('click', function () {
    $('.spci-tab').removeClass('disabled');
    $('.nav-link').removeClass('active');
    $('.tab-pane').removeClass('active');
    $('.tab-pane').removeClass('show');
    $('.spci-tab').addClass('active');
    $('.spci-tab').addClass('show');
  });
  $('.desc,.specifi').keyup(function () {
    var desc = $('.desc').val();
    var specifi = $('.specifi').val();
    if (specifi)
    {
      $('.pdt-continue4').removeClass('disabled');
      itemvalidate();
    } 
    else
    {
      $('.pdt-continue4').addClass('disabled');
      $('.img-tab').addClass('disabled');
      $('.active').removeClass('disabled');
    }
  });
  $('.pdt-continue4').on('click', function () {
    $('.img-tab').removeClass('disabled');
    $('.nav-link').removeClass('active');
    $('.tab-pane').removeClass('active');
    $('.tab-pane').removeClass('show');
    $('.img-tab').addClass('active');
    $('.img-tab').addClass('show');
  });
  // Add product
  $('#pdt-form').on('submit', function (e) {
    e.preventDefault();
    //var pdtval =$('#pdt-form').serialize();
    $.ajax({
      type: 'POST',
      url: 'AjaxFunction/AjaxProduct.php',
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
        $('#o-message').removeClass('alert alert-danger').addClass('alert alert-success');
        $('#o-message').html(response['message']);
        if (response['type'] == 1)
        {
              $('#pdt-form') [0].reset();
              $('.note-editable').html('');
              for (var i = 1; i <= 5; i++) 
              {
                  $('.img-upload'+i).attr('src', 'images/document-image.png');
              }
              $('.nav-tabs-custom li a:last').removeClass('active show')
              $('#ds5').removeClass('active show');
              $('#ds1').addClass('active show');
              $('.nav-tabs-custom li a:first').addClass('active show');
              $('.pdt-continue1').addClass('disabled');
              $('.pdt-continue2').addClass('disabled');
              $('.pdt-continue3').addClass('disabled');
              $('.pdt-continue4').addClass('disabled');
              $('.pdt-continue5').addClass('disabled');
              $('.menus').addClass('disabled');
              alertHelper();
        }
        alertHelper();
      }
      if (response['status'] == false)
      {
        if ($.isArray(response['message']))
        {
          $('#o-message').empty();
          $('#o-message').removeClass('alert alert-success').addClass('alert alert-danger');
          $.each(response['message'], function (index, value)
          {
            $('#o-message').append(value + '<br>');
          });
          alertHelper();
        } 
        else
        {
          $('#o-message').removeClass('alert alert-success').addClass('alert alert-danger');
          $('#o-message').html(response['message']);
          alertHelper();
        }
      }
    });
  });
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
  //Delete product
  $(document).on('click', '.delete-pdt', function () {
    //alert('dshgfdhg');
    $(this).parent().parent().remove();
    var pdtid = $(this).data('id');
    $.ajax({
      type: 'POST',
      url: 'AjaxFunction/DeleteFunction.php',
      data: {
        'id': pdtid,
        'method':'delete',
        'target':'product'
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
  function alertHelper()
  {
    setTimeout(function ()
    {
        $('#o-message').empty().removeClass();
        $('.successCls').removeClass('show').addClass('hide');
        $('.errorCls').removeClass('show').addClass('hide');
    }, 2000);
  }
});