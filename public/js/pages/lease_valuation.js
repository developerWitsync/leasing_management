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
        })
    }
}

// document ready function
$(function(){
    $('.load_lease_liability').each(function(index, value){
        var asset_id = $(this).data('asset_id');
        var that = $(this);
        $.ajax({
            url : '/lease/lease-valuation/lease-liability-asset/'+asset_id,
            dataType : 'json',
            beforeSend : function(){
                $(that).text('Calculating...');
            },
            success : function (response) {
                $(that).text(response['value']);
            }
        })
    });

});