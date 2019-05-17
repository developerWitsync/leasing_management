$(document).ready(function(){
    $("#dt1").datepicker({
        dateFormat: "dd-M-yy",
        changeYear: true,
        changeMonth: true,
        yearRange: '-100:+100',
        onSelect: function () {
            var dt2 = $('#dt2');
            var startDate = $(this).datepicker('getDate');
            var minDate = $(this).datepicker('getDate');
            var dt2Date = dt2.datepicker('getDate');
            //difference in days. 86400 seconds in day, 1000 ms in second
            var dateDiff = (dt2Date - minDate)/(86400 * 1000);

            //dt2 not set or dt1 date is greater than dt2 date
            if (dt2Date == null || dateDiff < 0) {
                dt2.datepicker('setDate', minDate);
            }
            //sets dt2 maxDate to the last day of 30 days window
            // dt2.datepicker('option', 'maxDate', startDate);
            //first day which can be selected in dt2 is selected date in dt1
            dt2.datepicker('option', 'minDate', minDate);
        }
    });
    $('#dt2').datepicker({
        dateFormat: "dd-M-yy",
        changeYear: true,
        changeMonth: true,
        yearRange: '-100:+100',
    });

    var __table_contractual = $("#report_contractual").dataTable({
        "responsive": true,
        "columns": [
            {
                "data": "id",
                render: function (data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
            },
            {"data": "single_asset.uuid", orderable: false},
            {"data": "lessor_name"},
            {"data": "single_asset.name", orderable: false},
            {"data": "single_asset.subcategory.title", orderable: false},
            {"data": "single_asset.lease_start_date", orderable: false},
            {"data": "single_asset.lease_end_date", orderable: false},
            {"data": "single_asset.country.name", orderable: false},
            {"data": "single_asset.location", orderable: false},
            {"data": "single_asset.specific_use.title", orderable: false},
            {"data": "initial_present_value"},
            {"data": "increase_decrease"},
            {
                "data": null, render: function (data, type, row, meta) {
                    return parseFloat(row.initial_present_value) + parseFloat(row.increase_decrease);
                },
                orderable: false
            },
            {"data": "lease_interest"},
            {"data": "contractual_lease_payment"},
            {"data":"closing_value_lease_liability", orderable : false},
            {"data":"initial_value_of_lease_asset"},
            {
                "data": null, render: function (data, type, row, meta) {
                    return parseFloat(row.initial_value_of_lease_asset) + parseFloat(row.increase_decrease);
                },
                orderable: false
            },
            {"data":"depreciation"},
            {
                "data": null, render: function (data, type, row, meta) {
                    return "";
                },
                orderable: false
            },
            {"data":"carrying_value_of_lease_asset", orderable : false},
            {"data":"adjustment_to_equity"},
            {"data": "charge_to_pl"}
        ],
        "scrollX": true,
        "processing": true,
        "serverSide": true,
        "ajax": _ajax_url
    });

    $(document.body).on('submit', '#reports_filter', function(e){
        e.preventDefault();
        if($('#dt1').val()!="" && $('#dt2').val() != "") {
            _ajax_url += '?start_date='+$('#dt1').val()+'&end_date='+$('#dt2').val();
            __table_contractual.api().ajax.url(_ajax_url).load();
        } else {
            alert('please select start and end date.')
        }
    });
});