$(document).ready(function(){
  // alert('fff');
$('#banner-form').on('submit',function(e){

  e.preventDefault();

    //var pdtval =$('#pdt-form').serialize();
// alert('ddd');
    $.ajax({

      type: 'POST',

      url: 'AjaxFunction/AjaxBanner.php',

      data: new FormData(this),

      contentType:false,

      cache:false,

      processData:false,

      dataType: 'json',
      beforeSend: function (x) {

                    $('.loading').css('visibility', 'visible');

                  }
    }).done(function (response)

    {
      console.log(response);
      if(response['status']== true)

        {

            $('#o-message').removeClass('alert alert-danger').addClass('alert alert-success');

            $('#o-message').html(response['message']);

            if(response['type']==1)
            {

                $("#banner-form")[0].reset();
            }
            alertHelper();
        }

        if(response['status']== false)

        {

            if($.isArray(response['message']))
            {
                $("#o-message").empty();
                $('#o-message').removeClass('alert alert-success').addClass('alert alert-danger');
                $.each(response['message'], function(index, value)
                {
                    $("#o-message").append(value + '<br>');
                });
                alertHelper();
            }
            else
            {
                $('#o-message').removeClass('alert alert-success').addClass('alert alert-danger');
                $("#o-message").html(response['message']);
                alertHelper();
            }
        }
    });
  });
$('.file_input').click(function() {

   // var id= $(this).attr('data-id');
// alert('fff');
  $('#file-input').change(function() {

     if (this.files && this.files[0]) {

        var reader = new FileReader();

        reader.onload = function(e) {

           $('.img-upload')

              .attr('src', e.target.result);

        };

        reader.readAsDataURL(this.files[0]);
     }

  });

});
$(document).on('click', '.delete-pdt', function(){

  //alert('dshgfdhg');
   $(this).parent().parent().remove();
   var id=$(this).data('id');

    $.ajax({

      type: 'POST',

      url: 'AjaxFunction/AjaxProduct.php',

      data: {'id':id,'method':'delete-pdt'},

      dataType: 'json',
      beforeSend: function (x) {

      $('.loading').css('visibility', 'visible');

    }
    }).done(function (response)

    {

      $('.loading').css('visibility', 'hidden');
     if(response['status'] == true)

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
$('.add-user').on('click',function(){

    var val = $('#user-form').serialize();
    $.ajax({

      type: 'POST',

      url: 'AjaxFunction/AjaxUser.php',

      data: val,

      dataType: 'json',
      beforeSend: function (x) {

                    $('.loading').css('visibility', 'visible');

                  }
    }).done(function (response)

    {

     if(response['status'] == true)

     {
      $('.loading').css('visibility', 'hidden');
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


$(document).on('click', '.delete-banner', function(){

   var id=$(this).data('id');

    $.ajax({

      type: 'POST',

      url: 'AjaxFunction/DeleteFunction.php',

      data: {
        'id': id,
        'method': 'delete',
        'target': 'banner'
      },

      dataType: 'json',
      beforeSend: function (x) {

        $('.loading').css('visibility', 'visible');

      }

    }).done(function (response)
    {
      $('.loading').css('visibility', 'hidden');
     if(response['status'] == true)

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

  setTimeout(function()

  {

    $('.successCls').removeClass('show').addClass('hide');
    $('.errorCls').removeClass('show').addClass('hide');

  },2000);

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

                  url: 'inc/manage-banner.php',

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