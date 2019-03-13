$(document).ready(function() {

    var html='<div id="pswd_info">\n' +
        '                                <strong>Password must meet the following requirements:</strong>\n' +
        '                                <ul>\n' +
        '                                    <li id="letter" class="invalid">At least <strong>one letter</strong></li>\n' +
        '                                    <li id="capital" class="invalid">At least <strong>one capital letter</strong></li>\n' +
        '                                    <li id="number" class="invalid">At least <strong>one number</strong></li>\n' +
        '                                    <li id="length" class="invalid">Be at least <strong>8 characters</strong></li>\n' +
        '                                </ul>\n' +
        '                            </div>';

    $('input[type=password]').keyup(function() {
        // keyup code here
        var pswd = $(this).val();
        ValidatePassword(pswd);
    }).focus(function() {
        // focus code here
        $(this).after(html);
        $('#pswd_info').show();
        var pswd = $(this).val();
        ValidatePassword(pswd);
    }).blur(function() {
        // blur code here
        $('#pswd_info').remove();
    });

    function ValidatePassword(pswd){
        //validate the length
        if ( pswd.length < 8 ) {
            $('#length').removeClass('valid').addClass('invalid');
        } else {
            $('#length').removeClass('invalid').addClass('valid');
        }
        //validate letter
        if ( pswd.match(/[A-z]/) ) {
            $('#letter').removeClass('invalid').addClass('valid');
        } else {
            $('#letter').removeClass('valid').addClass('invalid');
        }

        //validate capital letter
        if ( pswd.match(/[A-Z]/) ) {
            $('#capital').removeClass('invalid').addClass('valid');
        } else {
            $('#capital').removeClass('valid').addClass('invalid');
        }

        //validate number
        if ( pswd.match(/\d/) ) {
            $('#number').removeClass('invalid').addClass('valid');
        } else {
            $('#number').removeClass('valid').addClass('invalid');
        }
    }

});
