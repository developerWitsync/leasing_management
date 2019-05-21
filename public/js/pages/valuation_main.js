/**
 * Code for the new pages for the cards goes here...
 */
function fetchCategoryAssets(category_id, _is_capitalized) {
    if(category_id){

        if(_is_capitalized){
            var url = '/lease-valuation/valuation-capitalised/fetch-assets-for-category/'+category_id;
        } else {
            var url = '/lease-valuation/valuation-non-capitalised/fetch-assets-for-category/'+category_id;
        }

        $.ajax({
            url: url,
            success: function (response) {
                $('#append_here_'+category_id).html(response);
            }
        });
    }
}

function fetchShortTermLeaseAssets(){
    $.ajax({
        url: "/lease-valuation/valuation-non-capitalised/fetch-short-term-lease-assets",
        success: function (response) {
            $('#append_here_short_term').html(response);
        }
    });
}

function fetchLowValueLeaseAssets(){
    $.ajax({
        url: "/lease-valuation/valuation-non-capitalised/fetch-low-value-lease-assets",
        success: function (response) {
            $('#append_here_low_value').html(response);
        }
    });
}

$(document.body).on('click', '.pagination_changed', function (e) {
    e.preventDefault();
    var category_id = $(this).data('category');
    $.ajax({
        url: $(this).attr('href'),
        success: function (response) {
            $('#append_here_'+category_id).html(response);
        }
    });
});