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
        alert($(this).serialize());
        $.ajax({
            url : $(this).attr('action'),
            data : {
                lease_basis_title : $('#title').val()
            },
            dataType : 'json',
            type : 'post',
            success : function (response) {
                if(response['status']){
                    $('.alert-success').html(response['message']).show();
                    $('.status_sucess').show()
                } else {
                    $('.form-group').addClass('has-error');
                    var html = '<span class="help-block">\n' +
                        '                        <strong>'+response['errors']['lease_basis_title'][0]+'</strong>\n' +
                        '                    </span>';

                    $('#error_section').html(html);
                }
            }
        });
    })
});