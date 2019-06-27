$(function(){

    $('[data-toggle="tooltip"]').tooltip({
        html : true
    });

    /*Tab section*/
    $(".Privacy_left ul li a").on("click", function(e){
        e.preventDefault();
        var webend = $(this).attr("href");
        $(".PrivacyTab").hide(0);
        $(webend).show(0);
        $(".Privacy_left ul li a").removeClass("active");
        $(this).addClass("active");
    });

    $('.showHelp').tooltip();

    function showHelp(){
        //$(this).tooltip('show');
    }

    $("#workings_doc").change(function () {
        $("#workings_doc").show();
        var filename = $("#workings_doc").val();
        var or_name = filename.split("\\");
        $("#upload2").val(or_name[or_name.length - 1]);
    });

    $("#raise_ticket_form").on("submit", function(e){
        var that = $(this);
        e.preventDefault();
        var formData = new FormData($(this)[0]);
        $.ajax({
            url : $(this).attr("action"),
            data : formData,
            dataType : "json",
            type : "post",
            async: false,
            cache: false,
            contentType: false,
            enctype: "multipart/form-data",
            processData: false,
            beforeSend : function(){
                $(".error_raiase_ticket").remove();
                $(".raise_ticket_success_message").hide();
            },
            success : function (response) {
                if(response.status){
                    $(".raise_ticket_success_message").html(response.message).show();
                    $(that)[0].reset();
                } else {
                    $.each(response.errors, function(i, e){
                        var error_html = "<span class=\"error_raiase_ticket\">\n" +
                            "                        <span>"+e+"</span>\n" +
                            "                    </span>";
                       $("#"+i).after(error_html);
                    });
                }
            }
        });
    });

    $("#contactUsModal").on("hidden.bs.modal", function () {
        $(".raise_ticket_success_message").hide();
        $(".error_raiase_ticket").remove();
        $("#raise_ticket_form")[0].reset();
    });

    function resizeMenu(){
        var menuHeight = $('.dashLeft').height() + 20;

        var ifrsBxHeight = $('.ifrsBx').height() + 20;

        var leftMenuHeight = menuHeight - ifrsBxHeight - 64;

        $('.mainMenu').height(leftMenuHeight);
    }

    resizeMenu();

    $(window).resize(function(){
       resizeMenu();
    });

    $('.mainMenuItem a').on('click', function () {
        $(this).children('i').toggleClass('icon');
        $(this).next('.subMenuItem').slideToggle();
    });

    $('.mainMenuItem').each(function () {
        if($(this).children('a').hasClass('active')){
            $(this).children('a').find('i').toggleClass('icon');
            $(this).children('a').next('.subMenuItem').slideDown();
        }
    });

    //Toggle the add more on the Settings for the users
    $('.add_more').on('click', function(){
        $('.'+$(this).data('form')).toggle();
    })

    $('.edit_table_setting').on('click', function(){
        $.ajax({
            url : $(this).data('href'),
            type : 'get',
            success : function (response) {
                $('.modal-content').html(response);

                $("#myModal").modal('show');

                $("#myModal #start_date_edit").datepicker({
                    changeMonth: true,
                    changeYear: true
                });

                $("#myModal #end_date_edit").datepicker({
                    changeMonth: true,
                    changeYear: true
                });
            }
        });
    });

    $("#myModal").on('hidden.bs.modal', function(){
        $('.modal-content').html('');
    });

    $(document.body).on('submit', '#edit_settings', function (e) {
        e.preventDefault();
        $.ajax({
            url : $(this).attr('action'),
            data : {
                title : $('#title').val()
            },
            dataType : 'json',
            type : 'post',
            success : function (response) {
                if(response['status']){
                    $('.alert-success').html(response['message']).show();
                    $('.status_sucess').show();
                    setTimeout(function () {
                        window.location.reload();
                    }, 200);
                } else {
                    $('.form-group').addClass('has-error');
                    var html = '<span class="help-block">\n' +
                        '                        <strong>'+response['errors']['title'][0]+'</strong>\n' +
                        '                    </span>';

                    $('#error_section').html(html);
                }
            }
        });
    });
   $(document.body).on('submit', '#edit_settings1', function (e) {
        e.preventDefault();
        $.ajax({
            url : $(this).attr('action'),
            data : {
                start_date : $('#start_date_edit').val(),
                end_date : $('#end_date_edit').val()
            },
            dataType : 'json',
            type : 'post',
            success : function (response) {
                if(response['status']){
                    $('.alert-success').html(response['message']).show();
                    $('.status_sucess').show();
                    setTimeout(function () {
                        window.location.reload();
                    }, 200);
                } else {
                    
                    $('#edit_settings1 .form-group').addClass('has-error');
                    var html = '';
                    var html1 = '';
                    if(typeof(response['errors']['start_date'])!="undefined") {
                        html = '<span class="help-block">\n' +
                            '                        <strong>'+response['errors']['start_date'][0]+'</strong>\n' +
                            '                    </span>';
                    }

                    if(typeof(response['errors']['end_date'])!="undefined") {
                        html1 = '<span class="help-block">\n' +
                            '                        <strong>' + response['errors']['end_date'][0] + '</strong>\n' +
                            '                    </span>';
                    }
                    
                    $('#error_section').html(html);
                    $('#error_section1').html(html1);
                }
            }
        });
    });
       $(document.body).on('submit', '#edit_settings2', function (e) {
        e.preventDefault();
        $.ajax({
            url : $(this).attr('action'),
            data : {
                customer_name : $('#customer_name').val(),
                description : $('#description').val(),
                currency : $('#currency').val(),
                amount : $('#amount').val()
            },
            dataType : 'json',
            type : 'post',
            success : function (response) {
                if(response['status']){
                    $('.alert-success').html(response['message']).show();
                    $('.status_sucess').show();
                    setTimeout(function () {
                        window.location.reload();
                    }, 200);
                } else {
                    $('#edit_settings2 .form-group').addClass('has-error');
                    var html='<span class="help-block">\n' +
                        '                        <strong>'+response['errors']['start_date'][0]+'</strong>\n' +
                        '                    </span>';
                    var html1='<span class="help-block">\n' +
                        '                        <strong>'+response['errors']['end_date'][0]+'</strong>\n' +
                        '                    </span>';
                    
                    
                    $('#error_section').html(html);
                    $('#error_section1').html(html1);
                }
            }
        });
    });
    $(document.body).on("click", ".delete_settings", function () {
         var href = $(this).data('href');
         
        bootbox.confirm({
            message: "Are you sure that you want to delete this setting? These changes cannot be reverted.",
            buttons: {
                confirm: {
                    label: 'Yes',
                    className: 'btn btn-success'
                },
                cancel: {
                    label: 'No',
                    className: 'btn btn-danger'
                }
            },
            callback: function (result) {
                if(result) {
                    $.ajax({
                        url : href,
                        type : 'delete',
                        dataType : 'json',
                        success : function (response) {
                            if(response['status']) {
                                window.location.reload();
                            }
                        }
                    })
                }
            }
        });
    });

    $(document.body).on("click", ".add_intangible_asset", function () {
         var href = $(this).data('href');
         var status = $(this).data('status');
         bootbox.confirm({
            message: "Are you sure that you want to add this setting? These changes cannot be reverted.",
            buttons: {
                confirm: {
                    label: 'Yes',
                    className: 'btn btn-success'
                },
                cancel: {
                    label: 'No',
                    className: 'btn btn-danger'
                }
            },
            callback: function (result) {
                if(result) {
                    $.ajax({
                        url : href,
                        type : 'post',
                        dataType : 'json',
                        data : {
                            status : status
                        },
                        success : function (response) {
                            if(response['status']) {
                                window.location.reload();
                            }
                        }
                    })
                }
            }
        });
    });


    //sub_drop sub tables
    $('.sub_drop_escalation').on('click', function(){
        $(this).children('i').toggleClass('fa-plus-square');
        $(this).children('i').toggleClass('fa-minus-square');
        $(this).parent('td').parent('tr').next('tr.sub_table').toggle('500');
    });

    $('.sub-menu').on('click', function(){
        $(this).next('ul').toggle('slideUp');
    });

    $('.treeview').each(function(){
        if($(this).hasClass('active')){
            $(this).children('.treeview-menu').show();
        }
    });

});


$(document).ready(function(){
    $(".dash_navicon").click(function(){
        $(".dashLeft").toggleClass("leftNavOpen");
        $(".DashRight").toggleClass("dashRightBx");
        if($(".dashLeft").hasClass('leftNavOpen')){
            $('.ifrsBx').hide('150');
        } else {
            $('.ifrsBx').show('150');
        }
    });


});





/**
 * calculate the datedifference in between a start date and end date in terms of days, months and years
 * @param start_date
 * @param end_date
 * @returns {{years: number, months: number, days: number}}
 */
function dateDiff(start_date, end_date) {
    var year = end_date.getFullYear();
    var month = end_date.getMonth() + 1;
    var day = end_date.getDate() + 1;

    var yy = start_date.getFullYear();
    var mm = start_date.getMonth() + 1;
    var dd = start_date.getDate();

    var years, months, days;
    // months
    months = month - mm;
    if (day < dd) {
        months = months - 1;
    }
    // years
    years = year - yy;
    if (month * 100 + day < mm * 100 + dd) {
        years = years - 1;
        months = months + 12;
    }
    // days
    days = Math.floor((end_date.getTime() - (new Date(yy + years, mm + months - 1, dd)).getTime()) / (24 * 60 * 60 * 1000));
    //
    return {years: years, months: months, days: days};
}

function fetchExchangeRate(base, target, base_date, access_key, element_selector, endpoint){
    if(typeof(endpoint) == 'undefined') {
        endpoint = 'live';
    }
    if (location.protocol != 'https:'){
        var url = 'http://apilayer.net/api/' + endpoint + '?access_key=' + access_key + '&source='+base+'&currencies='+target;
    } else {
        var url = 'https://apilayer.net/api/' + endpoint + '?access_key=' + access_key + '&source='+base+'&currencies='+target;
    }
    if(typeof (base_date) != "undefined" && base_date!='') {
        url += '&date='+base_date;
    }

    var rate = 1;

    $.ajax({
        url: url,
        async: false,
        dataType: 'jsonp',
        success: function(result) {
            if(result.success) {
                rate = result['quotes'][base+target];
                $(element_selector).val(rate);
            }
        }
    });
}

function checklockperioddate(date, instance, _ajax_url) {
    $.ajax({
          type: "get",
          url: _ajax_url,
          data: "date="+date,
          success: function(response)
          {
              if(!response['status']){
                  var modal = bootbox.dialog({
                      message: response.message,
                      buttons: [
                          {
                              label: "OK",
                              className: "btn btn-success pull-left",
                              callback: function () {
                                  $('.lease_period').val('');
                                  $('.lease_period1').val('');
                                  $('.lease_period2').val('');

                              }
                          },

                      ],
                      show: false,
                      onEscape: function () {
                          modal.modal("hide");
                      }
                  });

                  modal.modal("show");
              }
          }
     });  
}

function showOverlayForAjax(){
    $('#overlay').show();
}

function removeOverlayAjax(){
    $('#overlay').hide();
}

function currencyFormat(price) {

    // Nine Zeroes for Billions
    return Math.abs(Number(price)) >= 1.0e+9

        ? (Math.abs(Number(price)) / 1.0e+9).toFixed(1) + "B"
        // Six Zeroes for Millions
        : Math.abs(Number(price)) >= 1.0e+6

            ? (Math.abs(Number(price)) / 1.0e+6).toFixed(1) + "M"
            // Three Zeroes for Thousands
            : Math.abs(Number(price)) >= 1.0e+3

                ? (Math.abs(Number(price)) / 1.0e+3).toFixed(1) + "K"

                : Math.abs(Number(price));

}