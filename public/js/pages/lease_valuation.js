
/**
 * Show present value calculus for an asset
 * @param asset_id
 */
function showPresentValueCalculus(asset_id = null){
    if(asset_id) {
        $.ajax({
            url : '/lease/lease-valuation/show-lease-liability-calculus/'+asset_id,
            beforeSend : function(){
                $('.calculus_spinner_'+asset_id).show();
            },
            success : function (response) {
                $('.current_modal_body').html(response);
                $('#myModal').modal('show');
                $('.calculus_spinner_'+asset_id).hide();
            }
        });
    }
}

/**
 * save the pv calculus to database as well
 * @param asset_id
 */
function savePresentValueCalculus(asset_id = null){
    if(asset_id) {
        $.ajax({
            url : '/lease/lease-valuation/save-lease-liability-calculus/'+asset_id
        });
    }
}

//@todo Need to put the ajax calls in callbacks so that we can re-use the same values again when required...
// document ready function
$(function(){

    showOverlayForAjax();

    var lease_liability_array = new Array();

    var total_lease_liability = 0;

    $('.load_payment_present_value').each(function(index, value){
        var asset_id = $(this).data('asset_id');
        var payment_id = $(this).data('payment_id');
        var that = $(this);
        $.ajax({
            url : '/lease/lease-valuation/lease-liability-asset/'+asset_id+'?payment='+payment_id,
            dataType : 'json',
            async : false,
            beforeSend : function(){
                $(that).text('Calculating...');
            },
            success : function (response) {
                $(that).text(response['value'].toFixed(2));
                total_lease_liability += parseFloat(response['value'].toFixed(2));
            }
        });
    });

    $('.load_termination_present_value').each(function(index, value){
        var asset_id = $(this).data('asset_id');
        var that = $(this);
        $.ajax({
            url : '/lease/lease-valuation/termination-present-value/'+asset_id,
            dataType : 'json',
            async : false,
            beforeSend : function(){
                $(that).text('Calculating...');
            },
            success : function (response) {
                $(that).text(response['value'].toFixed(2));
                total_lease_liability += parseFloat(response['value'].toFixed(2));
            }
        });
    });

    $('.load_residual_present_value').each(function(index, value){
        var asset_id = $(this).data('asset_id');
        var that = $(this);
        $.ajax({
            url : '/lease/lease-valuation/residual-present-value/'+asset_id,
            dataType : 'json',
            async : false,
            beforeSend : function(){
                $(that).text('Calculating...');
            },
            success : function (response) {
                $(that).text(response['value'].toFixed(2));
                total_lease_liability += parseFloat(response['value'].toFixed(2));
            }
        });
    });

    $('.load_purchase_present_value').each(function(index, value){
        var asset_id = $(this).data('asset_id');
        var that = $(this);
        $.ajax({
            url : '/lease/lease-valuation/purchase-present-value/'+asset_id,
            dataType : 'json',
            async : false,
            beforeSend : function(){
                $(that).text('Calculating...');
            },
            success : function (response) {
                $(that).text(response['value'].toFixed(2));
                total_lease_liability += parseFloat(response['value'].toFixed(2));
            }
        });
    });

    $('.load_lease_liability').each(function(index, value){
        var asset_id = $(this).data('asset_id');
        lease_liability_array[asset_id] = total_lease_liability;
    });


    // $('.load_lease_liability').each(function(index, value){
    //     var asset_id = $(this).data('asset_id');
    //     if(typeof (lease_liability_array[asset_id])!= "undefined") {
    //         $(this).text(lease_liability_array[asset_id].toFixed(2));
    //     } else {
    //         var that = $(this);
    //         $.ajax({
    //             url : '/lease/lease-valuation/lease-liability-asset/'+asset_id,
    //             dataType : 'json',
    //             async : false,
    //             beforeSend : function(){
    //                 $(that).text('Calculating...');
    //             },
    //             success : function (response) {
    //                 $(that).text(response['value'].toFixed(2));
    //                 lease_liability_array[asset_id] = response['value'];
    //             }
    //         });
    //     }
    // });

    var lease_valuation_array = new Array();
    $('.value_of_lease_asset').each(function(index, value){
        var asset_id = $(this).data('asset_id');

        if(typeof (lease_valuation_array[asset_id])!="undefined"){
            $(this).text(lease_valuation_array[asset_id].toFixed(2));
        } else {
            var that = $(this);
            $.ajax({
                url : '/lease/lease-valuation/lease-valuation-asset/'+asset_id,
                dataType : 'json',
                async : false,
                data : {
                    lease_liability_value : lease_liability_array[asset_id]
                },
                beforeSend : function(){
                    $(that).text('Calculating...');
                },
                success : function (response) {
                    $(that).text(response['value'].toFixed(2));
                    lease_valuation_array[asset_id] = response['value'];
                }
            });
        }

    });

    //Calculate and show the impairment_if_any on the impairment test
    $('.impairment_if_any').each(function(index, value){
        var asset_id = $(this).data('asset_id');
        var that = $(this);
        $.ajax({
            url : '/lease/lease-valuation/lease-impairment/'+asset_id,
            dataType : 'json',
            async : false,
            data : {
                lease_valuation_value : lease_valuation_array[asset_id]
            },
            beforeSend : function(){
                $(that).text('Calculating...');
            },
            success : function (response) {
                $(that).text(response['value'].toFixed(2));
            }
        });
    });

    $(document).ready(function () {
        removeOverlayAjax();
        $('.load_lease_liability').html(total_lease_liability);
        if(typeof existing_lease_liability != "undefined"){
            var increase_decrease = parseFloat(total_lease_liability) - parseFloat(existing_lease_liability);
            var initial_increase_decrease;
            $(".increase_decrease_part_first").html(increase_decrease.toFixed(2));


            //  calculate the new value of lease asset as per the conditions in the NL15SR ==> P11
            //  existing_carrying_value_of_lease_asset ==> P9
            //  increase_decrease ==> P10
            // existing_value_of_lease_asset ==> P8
            var new_value_of_lease_asset = 0;
            var charge_to_pl = '-';
            increase_decrease = parseFloat(increase_decrease);
            initial_increase_decrease = increase_decrease;
            if(increase_decrease < 0){
                increase_decrease = -1 * increase_decrease;

                if((existing_carrying_value_of_lease_asset - increase_decrease) > 0) {
                    //(P8 - P10)
                    new_value_of_lease_asset = existing_value_of_lease_asset - increase_decrease;
                    charge_to_pl = '-';
                } else if (existing_carrying_value_of_lease_asset - increase_decrease < 0){
                    // (P8 - P9)
                    new_value_of_lease_asset = existing_value_of_lease_asset - existing_carrying_value_of_lease_asset;

                    //(P10 - P9)
                    charge_to_pl = increase_decrease - existing_carrying_value_of_lease_asset;
                } else if(existing_carrying_value_of_lease_asset == 0){
                    //P8
                    new_value_of_lease_asset = existing_value_of_lease_asset;

                    //P10
                    charge_to_pl = increase_decrease;
                }

            } else {
                new_value_of_lease_asset = existing_value_of_lease_asset + increase_decrease;
                charge_to_pl = 0;
            }

            $('.new_value_of_lease_asset').html(new_value_of_lease_asset.toFixed(2));
            $('.charge_to_pl').html(charge_to_pl);
            var asset_id  = $('.load_lease_liability').data('asset_id');
            //update the asset with the new lease liability value and with the new value of lease asset
            $.ajax({
                url : '/lease/lease-valuation/update-asset-for-subsequent/'+asset_id,
                type : 'post',
                dataType : 'json',
                data : {
                    value_of_lease_asset : new_value_of_lease_asset,
                    lease_liability_value: total_lease_liability,
                    increase_decrease : initial_increase_decrease
                },
                success : function(response){

                }
            });
        }
    });


});

