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
            }
        });
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
});