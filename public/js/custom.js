$(function(){

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
                $("#audit_year1_ended_on1").datepicker({
                    changeMonth: true,
                    changeYear: true
                });

                $("#audit_year2_ended_on1").datepicker({
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
                audit_year1_ended_on1 : $('#audit_year1_ended_on1').val(),
                audit_year2_ended_on1 : $('#audit_year2_ended_on1').val()
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
                    var html='<span class="help-block">\n' +
                        '                        <strong>'+response['errors']['audit_year1_ended_on1'][0]+'</strong>\n' +
                        '                    </span>';
                    var html1='<span class="help-block">\n' +
                        '                        <strong>'+response['errors']['audit_year2_ended_on1'][0]+'</strong>\n' +
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


    //sub_drop sub tables
    $('.sub_drop_escalation').on('click', function(){
        $(this).children('i').toggleClass('fa-plus-square');
        $(this).children('i').toggleClass('fa-minus-square');
        $(this).parent('td').parent('tr').next('tr.sub_table').toggle('500');
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
    var day = end_date.getDate();

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