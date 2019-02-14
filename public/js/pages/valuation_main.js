$(document).ready(function () {

    function convertToExchangeRate(data, type, exchange_rate){
        if(exchange_rate != 1){
            return parseFloat(exchange_rate) * parseFloat(data);
        } else {
            return data;
        }
    }

    if(_is_capitalized) {
        var columns = [
            {"data": "uuid"},
            {"data": "lease.lessor_name", "sortable": false},
            {"data": "name"},
            {"data": "category.title", "sortable": false},
            {"data": "specific_use.title", "sortable": false},
            {"data": "country.name", "sortable": false},
            {"data": "location"},
            {"data": "start_date", name: "accural_period"},
            {"data": "remaining_term", name: "remaining_term"},
            {"data": "lease_select_discount_rate.discount_rate_to_use", "sortable": false},


            {"data": "foreign_initial_lease_currency", "sortable": false},
            {"data": "foreign_initial_undiscounted_lease_liability", "sortable": false},
            {"data": "foreign_initial_present_value_of_lease_liability", "sortable": false},
            {"data": "foreign_initial_value_of_lease_asset", "sortable": false},

            {"data": "lease_select_low_value.undiscounted_lease_payment", "sortable": false},
            {"data": "lease_liablity_value", "sortable": false},
            {"data": "value_of_lease_asset", "sortable": false},


            {
                "data": "exchange_rate", "sortable": false
            }, //reporting currency should be 1 in case the foreign currency is not applied
            {
                "data": "initial_undiscounted_lease_liability",
                "sortable": false,
                "render": function (data, type, row) {
                    return convertToExchangeRate(data, type, row['exchange_rate']);
                }
            }, //reporting currency
            {
                "data": "initial_present_value_of_lease_liability",
                "sortable": false,
                "render": function (data, type, row) {
                    return convertToExchangeRate(data, type, row['exchange_rate']);
                }
            }, //reporting currency
            {
                "data": "initial_value_of_lease_asset", "sortable": false, "render": function (data, type, row) {
                    return convertToExchangeRate(data, type, row['exchange_rate']);
                }
            }, //reporting currency
            {
                "data": "subsequent_modification_effective_date", "sortable": false //Effective Date
            },
            {
                "data": "subsequent_modification_exchange_rate", "sortable": false //Exchange Rate
            },
            {
                "data": "lease_select_low_value.undiscounted_lease_payment",
                "sortable": false,
                "render": function (data, type, row) {
                    if (row['has_subsequent_modifications']) {
                        return convertToExchangeRate(data, type, row['subsequent_modification_exchange_rate']);
                    } else {
                        return "N/A";
                    }
                }
            }, //reporting currency
            {
                "data": "lease_liablity_value", "sortable": false, "render": function (data, type, row) {
                    if (row['has_subsequent_modifications']) {
                        return convertToExchangeRate(data, type, row['subsequent_modification_exchange_rate']);
                    } else {
                        return "N/A";
                    }
                }
            }, //reporting currency
            {
                "data": "value_of_lease_asset", "sortable": false, "render": function (data, type, row) {
                    if (row['has_subsequent_modifications']) {
                        return convertToExchangeRate(data, type, row['subsequent_modification_exchange_rate']);
                    } else {
                        return "N/A";
                    }
                }
            } //reporting currency

        ];
        var columnDefs = [{
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
        }];
    } else {
        var columns = [
            {"data": "uuid"},
            {"data": "lease.lessor_name", "sortable": false},
            {"data": "name"},
            {"data": "category.title", "sortable": false},
            {"data": "specific_use.title", "sortable": false},
            {"data": "country.name", "sortable": false},
            {"data": "location"},
            {"data": "start_date", name: "accural_period"},
            {"data": "remaining_term", name: "remaining_term"},
            {"data": "lease_select_discount_rate.discount_rate_to_use", "sortable": false},


            {"data": "foreign_initial_lease_currency", "sortable": false},
            {"data": "foreign_initial_undiscounted_lease_liability", "sortable": false},

            {"data": "lease_select_low_value.undiscounted_lease_payment", "sortable": false},


            {
                "data": "exchange_rate", "sortable": false
            }, //reporting currency should be 1 in case the foreign currency is not applied
            {
                "data": "initial_undiscounted_lease_liability",
                "sortable": false,
                "render": function (data, type, row) {
                    return convertToExchangeRate(data, type, row['exchange_rate']);
                }
            }, //reporting currency
            {
                "data": "subsequent_modification_effective_date", "sortable": false //Effective Date
            },
            {
                "data": "subsequent_modification_exchange_rate", "sortable": false //Exchange Rate
            },
            {
                "data": "lease_select_low_value.undiscounted_lease_payment",
                "sortable": false,
                "render": function (data, type, row) {
                    if (row['has_subsequent_modifications']) {
                        return convertToExchangeRate(data, type, row['subsequent_modification_exchange_rate']);
                    } else {
                        return "N/A";
                    }
                }
            } //reporting currency
        ];

        var columnDefs = [{
            "targets" : 12,
            "render" : function( data, type, row ){
                if(row['has_subsequent_modifications']){
                    return row["lease_select_low_value"]["undiscounted_lease_payment"];
                } else {
                    return "N/A";
                }
            }
        }];
    }

    $("#lease_valuation").DataTable({
        responsive: true,
        columnDefs : columnDefs,
        "scrollX": true,
        "columns": columns,
        "processing": true,
        "serverSide": true,
        "ajax": _data_table_url,
    });

});