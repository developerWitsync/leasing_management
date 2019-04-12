// Create a closure
(function(){
    // Your base, I'm in it!
    var originalAddClassMethod = jQuery.fn.addClass;

    jQuery.fn.addClass = function(){
        // Execute the original method.
        var result = originalAddClassMethod.apply( this, arguments );

        // trigger a custom event
        jQuery(this).trigger("cssClassChanged");

        // return the original result
        return result;
    }
})();

// Create a closure
(function(){
    // Your base, I'm in it!
    var originalAddClassMethod = jQuery.fn.removeClass;

    jQuery.fn.removeClass = function(){
        // Execute the original method.
        var result = originalAddClassMethod.apply( this, arguments );

        // trigger a custom event
        jQuery(this).trigger("cssClassChanged");

        // return the original result
        return result;
    };
})();

// document ready function
$(function(){

    $(".amount_based_escalation_amount").bind("cssClassChanged", function(){
        if(!$(this).hasClass("hidden")) {
            $(".see_escalation_chart").removeClass("hidden");
        } else {
            //check if the  total_escalation_rate is visible
            if($(".total_escalation_rate").hasClass("hidden") && $(".inconsistently_applied").hasClass("hidden")) {
                $(".see_escalation_chart").addClass("hidden");
            }
        }
    });

    $(".total_escalation_rate").bind("cssClassChanged", function(){
        if(!$(this).hasClass("hidden")) {
            $(".see_escalation_chart").removeClass("hidden");
        } else {
            //check if the  amount_based_escalation_amount is visible
            if($(".amount_based_escalation_amount").hasClass("hidden") && $(".inconsistently_applied").hasClass("hidden")) {
                $(".see_escalation_chart").addClass("hidden");
            }
        }
    });

    $(".inconsistently_applied").bind("cssClassChanged", function(){
        if(!$(this).hasClass("hidden")) {
            $(".see_escalation_chart").removeClass("hidden");
        } else {
            //check if the  amount_based_escalation_amount is visible
            if($(".total_escalation_rate").hasClass("hidden")  && $(".amount_based_escalation_amount").hasClass("hidden")) {
                $(".see_escalation_chart").addClass("hidden");
            }
        }
    });

});
/**
 * Show payment Annexure in case of escalation is not applicable
 */

$(".show_payment_annexure").on("click", function(){
    $.ajax({
        url : _show_payment_annexure_url,
        data : $("form").serialize(),
        type : "get",
        success : function(response){
            setTimeout(function () {
                $(".escalation_chart_modal_body").html(response);
                $("#myModal").modal("show");
            }, 100);
        }
    });
});


$(".show_escalation_chart").on("click", function(){
    $.ajax({
        url : _show_escalation_char_url,
        data : $("form").serialize(),
        type : "get",
        beforeSend: function(){
            showOverlayForAjax();
        },
        success : function(response){
            setTimeout(function () {
                $(".escalation_chart_modal_body").html(response);

                $("#myModal").modal("show");
                removeOverlayAjax();
            }, 100);
        }
    });
});

$(".compute_escalation").on("click", function(){
    $.ajax({
        url : _compute_escalation_url,
        data : $("form").serialize(),
        type : "get",
        dataType : "json",
        beforeSend : function(){
            $(".error_via_ajax").remove();
            $(".computed_fields").addClass("hidden");
        },
        success : function(response){
            if(response["status"]) {

                $(".computed_fields").removeClass("hidden");
                $("#computed_total").val(response["computed_total"]);

                $(".see_escalation_chart").removeClass("hidden");

            } else {
                $(".see_escalation_chart").addClass("hidden");
                $.each(response["errors"], function (i,e) {
                    if($('input[name="'+i+'"]').length ){
                        $('input[name="'+i+'"]').after('<span class="help-block error_via_ajax" style="color:red">\n' +
                            '<strong>'+e+'</strong>\n' +
                            '</span>');
                    }
                });
            }
        }
    });
});

$(document).on('click', 'input[type="checkbox"][name="is_escalation_applicable"]', function() {
    $('input[type="checkbox"][name="is_escalation_applicable"]').not(this).prop('checked', false);

    if($(this).is(':checked') && $(this).val() == 'yes') {
        $('.hidden_fields').removeClass('hidden');
        $('.see_payment_annexure').addClass('hidden');
    } else {
        $('.inconsistently_applied').addClass('hidden');
        $('.hidden_fields').addClass('hidden');
        $('.see_payment_annexure').removeClass('hidden');
    }
});

$(document).ready(function () {

    //Code to load the form filled up in case of the inconsistent inputs were selected previously
    if($('input[type="checkbox"][name="is_escalation_applied_annually_consistently"]:checked').val() == 'no') {
        _inconsistent_escalation_inputs = JSON.parse(_inconsistent_escalation_inputs);

        //Make the Escalations Frequency dropdowns to appear or not to appear based upon the inputs that were used previously..
        $.each(_inconsistent_escalation_inputs.inconsistent_escalation_frequency, function(i, e){
            if(e) {
                $('select[name="inconsistent_escalation_frequency['+i+'][]"]').val(e);
                showHideInconsistentEscalation($('select[name="inconsistent_escalation_frequency['+i+'][]"]'));
            }
        });

        //populate the escalation dates that has been inputted by the user on create escalations
        for (var key in _inconsistent_escalation_inputs.inconsistent_effective_date) {
            if (_inconsistent_escalation_inputs.inconsistent_effective_date.hasOwnProperty(key)) {
                for(var date in _inconsistent_escalation_inputs.inconsistent_effective_date[key]){
                    $('select[name="inconsistent_effective_date['+key+'][]"]:eq('+date+')').val(_inconsistent_escalation_inputs.inconsistent_effective_date[key][date]);
                }
            }
        }

        //populate the Escalation Amount that has been inputted by the user on create escalations
        for (var key in _inconsistent_escalation_inputs.inconsistent_escalated_amount) {
            if (_inconsistent_escalation_inputs.inconsistent_escalated_amount.hasOwnProperty(key)) {
                for(var amount in _inconsistent_escalation_inputs.inconsistent_escalated_amount[key]){
                    $('input[name="inconsistent_escalated_amount['+key+'][]"]:eq('+amount+')').val(_inconsistent_escalation_inputs.inconsistent_escalated_amount[key][amount]);
                }
            }
        }


        //populate the Escalation Percentages When the escalation is applied inconsistently and percentage based [[FIXED RATE]]
        for (var key in _inconsistent_escalation_inputs.inconsistent_fixed_rate) {
            if (_inconsistent_escalation_inputs.inconsistent_fixed_rate.hasOwnProperty(key)) {
                for(var fixed_rate in _inconsistent_escalation_inputs.inconsistent_fixed_rate[key]){
                    $('select[name="inconsistent_fixed_rate['+key+'][]"]:eq('+fixed_rate+')').val(_inconsistent_escalation_inputs.inconsistent_fixed_rate[key][fixed_rate]);
                }
            }
        }

        //populate the Escalation Percentages When the escalation is applied inconsistently and percentage based [[VARIABLE RATE]]
        for (var key in _inconsistent_escalation_inputs.inconsistent_current_variable_rate) {
            if (_inconsistent_escalation_inputs.inconsistent_current_variable_rate.hasOwnProperty(key)) {
                for(var variable_rate in _inconsistent_escalation_inputs.inconsistent_current_variable_rate[key]){
                    $('select[name="inconsistent_current_variable_rate['+key+'][]"]:eq('+variable_rate+')').val(_inconsistent_escalation_inputs.inconsistent_current_variable_rate[key][variable_rate]);
                }
            }
        }

        //populate the Escalation Percentages When the escalation is applied inconsistently and percentage based [[TOTAL ESCALATION RATE]]
        for (var key in _inconsistent_escalation_inputs.inconsistent_total_escalation_rate) {
            if (_inconsistent_escalation_inputs.inconsistent_total_escalation_rate.hasOwnProperty(key)) {
                for(var total_rate in _inconsistent_escalation_inputs.inconsistent_total_escalation_rate[key]){
                    $('input[name="inconsistent_total_escalation_rate['+key+'][]"]:eq('+total_rate+')').val(_inconsistent_escalation_inputs.inconsistent_total_escalation_rate[key][total_rate]);
                }
            }
        }

    }




    $('.escalation_frequency').on('change', function(){
        showHideInconsistentEscalation($(this));
    });

    function showHideInconsistentEscalation(that){
        var _current_frequency = $(that).val();
        var _current_year = $(that).data('year');

        if(_current_frequency){
            $('.escalation_inconsistent_table_'+_current_year).removeClass('hidden');
        } else {
            $('.escalation_inconsistent_table_'+_current_year).addClass('hidden');
        }

        var _current_variable_rate_select = '';

        var _fixed_rate_select = '';


        var _select_payment_dates = '<select class="form-control" name="inconsistent_effective_date[YEAR][]">\n';
        if(_is_subsequent_modification &&  typeof(_subsequent_modification_year)!="undefined" &&_current_year < _subsequent_modification_year){
            var _select_payment_dates = '<select class="form-control" name="inconsistent_effective_date[YEAR][]" readonly="readonly">\n';
        } else {
            var _select_payment_dates = '<select class="form-control" name="inconsistent_effective_date[YEAR][]">\n';
        }

        _select_payment_dates += '<option value="">--Select Escalation Effective On Date--</option>';

        var datesArray = JSON.parse(paymentDueDates);

        for(i = 0; i < datesArray.length; i++){
            if(datesArray[i].indexOf(_current_year) > -1) {
                if(_is_subsequent_modification &&  typeof(_subsequent_modification_applicable_from)!="undefined" && _subsequent_modification_applicable_from >= datesArray[i]){
                    _select_payment_dates += '<option value="'+datesArray[i]+'" disabled="disabled">'+datesArray[i]+'</option>';
                } else {
                    _select_payment_dates += '<option value="'+datesArray[i]+'">'+datesArray[i]+'</option>';
                }
            }
        }

        _select_payment_dates += '</select>';

        if($('select[name="escalation_basis"]').val()=='2'){
            //show the amount based htmls
            var theads_array = new Array('Escalation Effective Date', 'Currency', 'Escalation Amount');

            var _clone_html =
                '<tr>\n' +
                '        <td>\n' + _select_payment_dates +
                '        </td>\n' +
                '        <td>\n' +
                '            <input type="text" name="inconsistent_amount_based_currency[YEAR][]" value="'+lease_contract_id+'" class="form-control" placeholder="Currency" readonly="readonly">\n' +
                '        </td>\n' +
                '        <td>\n' +
                '            <input type="text" name="inconsistent_escalated_amount[YEAR][]" class="form-control" placeholder="Enter Amount of Increase">\n' +
                '        </td>\n' +
                '</tr>';


        } else {
            var theads_array = new Array('Escalation Effective Date', 'Specify Fixed Rate (%P.A)', 'Specify the Current Variable Rate (%P.A)', 'Total Escalation Rate (%PA)');
            //append the percentage based htlms here
            //check for the variable and fixed rate basis
            var _escalation_rate_type = $('select[name="escalation_rate_type"]').val();
            if(_escalation_rate_type == 1 || _escalation_rate_type == 3) {
                //both the variable and fixed rates will appear
                _fixed_rate_select = _global_fixed_rate_select;
            }

            if(_escalation_rate_type == 2 || _escalation_rate_type == 3) {
                //both the variable and fixed rates will appear
                _current_variable_rate_select = _global_current_variable_rate_select;
            }


            var _clone_html =
                '<tr>\n' +
                '        <td>\n' + _select_payment_dates +
                '        </td>\n' +
                '        <td>\n' + _fixed_rate_select +
                '        </td>\n' +
                '        <td>\n' + _current_variable_rate_select +
                '        </td>\n' +
                '        <td>\n' +
                '            <input type="text" name="inconsistent_total_escalation_rate[YEAR][]" class="form-control" placeholder="Total Escalation Rate">\n' +
                '        </td>\n' +
                '</tr>';

        }

        var thead_html = "";

        for(i = 0; i< theads_array.length; i++){
            thead_html += '<th>'+theads_array[i]+'</th>';
        }

        $('.theads_escalations').html(thead_html);

        _clone_html =  _clone_html.replace(new RegExp("YEAR", 'g'), _current_year);

        $('.replace_with_'+_current_year).html(_clone_html.repeat(_current_frequency));



    }

    function refreshInconsistentEscalations(){
        if($('input[type="checkbox"][name="is_escalation_applied_annually_consistently"]:checked').val() == 'no') {
            $('.escalation_frequency').each(function (i, e) {
                showHideInconsistentEscalation($(this));
            });
        }
    }

    //toggle Rate Type Dropdown on the basis of the Escalation Basis selected
    $('select[name="escalation_basis"]').on('change', function () {
        if($(this).val() == '1') {
            //Rate Based
            $('.escalation_rate_type').removeClass('hidden');
            $('.amount_based_fields').addClass('hidden');
            $('.amount_based_escalation_amount').addClass('hidden');

        } else {
            //amount based
            $('.is_j_12_y_e_s_fixed_rate').addClass('hidden');
            $('.is_j_12_y_e_s_variable_rate').addClass('hidden');

            $('select[name="fixed_rate"]').val('');
            $('select[name="current_variable_rate"]').val('');

            $('.total_escalation_rate').addClass('hidden');
            $('input[name="total_escalation_rate"]').val('');
            $('.escalation_rate_type').addClass('hidden');

            $('select[name="escalation_rate_type"]').val('');

            // show the amount based input fields here
            if($('input[type="checkbox"][name="is_escalation_applied_annually_consistently"]:checked').val() == 'yes'){
                $('.amount_based_fields').removeClass('hidden');
                $('.amount_based_escalation_amount').removeClass('hidden');
            } else {
                $('.amount_based_fields').addClass('hidden');
                $('.amount_based_escalation_amount').addClass('hidden');
            }
        }

        refreshInconsistentEscalations();
    });

    //show the pop up and confirm messages on the Escalation Consistently Annually Applied Post Effective Date checkbox
    $(document).on('click', 'input[type="checkbox"][name="is_escalation_applied_annually_consistently"]', function() {
        $('input[type="checkbox"][name="is_escalation_applied_annually_consistently"]').not(this).prop('checked', false);
        var that = $(this);
        if($(this).is(':checked') && $(this).val() == 'yes') {
            bootbox.confirm({
                message: "Every Year Escalation will be applied at the same rate on Previous Year Lease Payment. Are you sure?",
                buttons: {
                    confirm: {
                        label: 'Yes' ,
                        className: 'btn-success'
                    },
                    cancel: {
                        label: 'No',
                        className: 'btn-danger'
                    }
                },
                callback: function (result) {
                    if(!result) {
                        confirmWhenEscalationAppliedInconsistently();
                    } else {
                        //check if the fixed/fixed & variable selected in j11
                        if($('select[name="escalation_rate_type"]').val() == '1' || $('select[name="escalation_rate_type"]').val() == '3') {
                            $('.is_j_12_y_e_s_fixed_rate').removeClass('hidden');
                            $('.total_escalation_rate').removeClass('hidden');
                        }

                        if($('select[name="escalation_rate_type"]').val() == '2' || $('select[name="escalation_rate_type"]').val() == '3') {
                            $('.is_j_12_y_e_s_variable_rate').removeClass('hidden');
                            $('.total_escalation_rate').removeClass('hidden');
                        }

                        //check if escalation_basis is amount based
                        if($('select[name="escalation_basis"]').val() == '2') {
                            $('.amount_based_fields').removeClass('hidden');
                            $('.amount_based_escalation_amount').removeClass('hidden');
                        } else {
                            $('.amount_based_fields').addClass('hidden');
                            $('.amount_based_escalation_amount').addClass('hidden');
                        }

                        $('.computed_fields').removeClass('hidden');

                        $('.inconsistently_applied').addClass('hidden');
                    }
                }
            });
        } else if($(this).is(':checked') && $(this).val() == 'no'){
            confirmWhenEscalationAppliedInconsistently();
        } else {
            $('.is_j_12_y_e_s_fixed_rate').addClass('hidden');
            $('.is_j_12_y_e_s_variable_rate').addClass('hidden');

            $('select[name="current_fixed_rate"]').val('');
            $('select[name="current_variable_rate"]').val('');

            $('.total_escalation_rate').addClass('hidden');
            $('input[name="total_escalation_rate"]').val('');

        }
    });

    /**
     * confirmation pop up when no is selected for escalations applied inconsistently.
     */
    function confirmWhenEscalationAppliedInconsistently(){
        //need to show other confirm box here
        bootbox.confirm({
            message: "Are You Sure that the Escalation applied inconsistently?",
            buttons: {
                confirm: {
                    label: 'Yes',
                    className: 'btn-success'
                },
                cancel: {
                    label: 'No',
                    className: 'btn-danger'
                }
            },
            callback: function (result) {
                $('.is_j_12_y_e_s_fixed_rate').addClass('hidden');
                $('.is_j_12_y_e_s_variable_rate').addClass('hidden');

                $('select[name="fixed_rate"]').val('');
                $('select[name="current_variable_rate"]').val('');

                $('.total_escalation_rate').addClass('hidden');
                $('input[name="total_escalation_rate"]').val('');

                //check if escalation_basis is amount based
                if($('select[name="escalation_basis"]').val() == '2') {
                    $('.amount_based_fields').addClass('hidden');
                    $('.amount_based_escalation_amount').addClass('hidden');
                }

                if(result) {
                    $('input[type="checkbox"][name="is_escalation_applied_annually_consistently"][value="yes"]').prop('checked', false);
                    $('input[type="checkbox"][name="is_escalation_applied_annually_consistently"][value="no"]').prop('checked', true);

                    $('.computed_fields').addClass('hidden');

                    //show the inconsistently form fields here
                    $('.inconsistently_applied').removeClass('hidden');

                    refreshInconsistentEscalations();
                } else {
                    $('input[type="checkbox"][name="is_escalation_applied_annually_consistently"]').prop('checked', false);
                    $('.computed_fields').removeClass('hidden');

                    $('.inconsistently_applied').addClass('hidden');
                }
            }
        });
    }

    $('select[name="escalation_rate_type"]').on('change', function(){
        var checkbox_value = $('input[type="checkbox"][name="is_escalation_applied_annually_consistently"]:checked').val();

        //If YES Selected in J12.1 and Fixed rate / Fixed & Variable in J11
        if(($(this).val() == '1' || $(this).val() == '3') && typeof (checkbox_value)!= "undefined" && checkbox_value == 'yes'){
            $('.is_j_12_y_e_s_fixed_rate').removeClass('hidden');
        } else {
            $('select[name="fixed_rate"]').val('');
            $('.is_j_12_y_e_s_fixed_rate').addClass('hidden');
        }

        //If YES Selected in J12.1 and Variable rate / Fixed & Variable in J11
        if(($(this).val() == '2' || $(this).val() == '3') && typeof (checkbox_value)!= "undefined" && checkbox_value == 'yes'){
            $('select[name="fixed_rate"]').val('');
            $('.is_j_12_y_e_s_variable_rate').removeClass('hidden');
        } else {
            $('select[name="current_variable_rate"]').val('');
            $('.is_j_12_y_e_s_variable_rate').addClass('hidden');
        }

        if($(this).val() != '' && checkbox_value == "yes"){
            $('.total_escalation_rate').removeClass('hidden');
            calculateTotalEscalationRate();
        } else {
            $('.total_escalation_rate').addClass('hidden');
        }

        refreshInconsistentEscalations();
    });

    //calculate total escalation rate based upon the fixed and variable rates
    $('select[name="current_variable_rate"] , select[name="fixed_rate"]').on('change', function(){
        calculateTotalEscalationRate();
    });

    /**
     * calculate the Total escalation rate when the percentage rate is selected and when the Yes is selected
     */
    function calculateTotalEscalationRate(){
        var current_variable_rate = $('select[name="current_variable_rate"]').val();
        var fixed_rate = $('select[name="fixed_rate"]').val();
        var total = parseInt(((current_variable_rate!="")?current_variable_rate:0)) + parseInt(((fixed_rate !="")?fixed_rate:0));
        $('input[name="total_escalation_rate"]').val(total);
    }
});