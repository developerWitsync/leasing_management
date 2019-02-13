$(document).ready(function () {

    var columns = [
        { "data" : "uuid"},
        { "data" : "lease.lessor_name", "sortable": false},
        { "data" : "name"},
        { "data" : "category.title" , "sortable": false},
        { "data" : "specific_use.title" , "sortable": false},
        { "data" : "country.name" , "sortable": false},
        { "data" : "location"},
        { "data" : "start_date", name : "accural_period"},
        { "data" : "remaining_term" , name: "remaining_term"},
        { "data" : "lease_select_discount_rate.discount_rate_to_use", "sortable": false},


        { "data" : "foreign_initial_lease_currency", "sortable": false},
        { "data" : "foreign_initial_undiscounted_lease_liability", "sortable": false },
        { "data" : "foreign_initial_present_value_of_lease_liability", "sortable": false },
        { "data" : "foreign_initial_value_of_lease_asset", "sortable": false },

        { "data" : "lease_select_low_value.undiscounted_lease_payment", "sortable": false },
        { "data" : "lease_liablity_value", "sortable": false },
        { "data" : "value_of_lease_asset", "sortable": false },


        { "data" : "initial_lease_currency", "sortable": false},
        { "data" : "initial_undiscounted_lease_liability", "sortable": false },
        { "data" : "initial_present_value_of_lease_liability", "sortable": false },
        { "data" : "initial_value_of_lease_asset", "sortable": false },

        { "data" : "lease_select_low_value.undiscounted_lease_payment", "sortable": false },
        { "data" : "lease_liablity_value", "sortable": false },
        { "data" : "value_of_lease_asset", "sortable": false }



    ];

    $("#lease_valuation").DataTable({
        responsive: true,
        "columnDefs" : [{
            "targets" : 14,
            "render" : function( data, type, row ){
                if(row['has_subsequent_modifications']){
                    return row["lease_select_low_value"]["undiscounted_lease_payment"];
                } else {
                    return "N/A";
                }
            }
        },{
            "targets" : 15,
            "render" : function( data, type, row ){
                if(row['has_subsequent_modifications']){
                    return row["lease_liablity_value"];
                } else {
                    return "N/A";
                }
            }
        },{
            "targets" : 16,
            "render" : function( data, type, row ){
                if(row['has_subsequent_modifications']){
                    return row["value_of_lease_asset"];
                } else {
                    return "N/A";
                }
            }
        }],
        "scrollX": true,
        "columns": columns,
        "processing": true,
        "serverSide": true,
        "ajax": _data_table_url,
    });

});