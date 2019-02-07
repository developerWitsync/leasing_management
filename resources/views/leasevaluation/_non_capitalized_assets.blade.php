<?php
/**
 * Created by PhpStorm.
 * User: himanshur
 * Date: 6/2/19
 * Time: 5:17 PM
 */
?>
<table id="lease_valuation" class="table table-condensed table-bordered">
    <thead>
    <th>&nbsp;</th>
    <th>Lease Contract Reference Number</th>
    <th>Lessor Name</th>
    <th>Underlying Lease Asset Name</th>
    <th>Underlying Lease Asset Type</th>
    <th>Purpose of Lease Asset</th>
    <th>Country</th>
    <th>Location</th>
    <th>Lease Start Date</th>
    <th>Remaining Lease Term</th>
    <th>Discount Rate</th>
    </thead>
</table>
@section('footer-script')
    <script src="{{ asset('assets/plugins/bootbox/bootbox.min.js') }}"></script>
    <script src="{{ asset('js/jquery-ui.js') }}"></script>
    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.16/js/dataTables.bootstrap4.min.js"></script>
    <script>
        $(document).ready(function () {
            var table_lease_valuation = $('#lease_valuation').DataTable({
                responsive: true,
                "columns": [
                    {
                        "className":      'details-control',
                        "orderable":      false,
                        "data":           null,
                        "defaultContent": ''
                    },
                    { "data" : "uuid"},
                    { "data" : "lease.lessor_name", 'sortable': false},
                    { "data" : "name"},
                    { "data" : "category.title" , 'sortable': false},
                    { "data" : "specific_use.title" , 'sortable': false},
                    { "data" : "country.name" , 'sortable': false},
                    { "data" : "location"},
                    { "data" : "start_date"},
                    { "data" : "remaining_lease_term"},
                    { "data" : "lease_select_discount_rate.discount_rate_to_use", 'sortable': false},
                ],
                "processing": true,
                "serverSide": true,
                "ajax": "{{ route('leasevaluation.fetchassets',['capitalized' => $capitalized, 'category_id' => (request()->has('id'))?request()->id:'all']) }}"

            });

            // Add event listener for opening and closing details
            $('#lease_valuation tbody').on('click', 'td.details-control', function () {
                var tr = $(this).closest('tr');
                var row = table_lease_valuation.row( tr );

                if ( row.child.isShown() ) {
                    // This row is already open - close it
                    row.child.hide();
                    tr.removeClass('shown');
                }
                else {
                    // Open this row
                    row.child( format(row.data()) ).show();
                    tr.addClass('shown');
                }
            } );


            function format ( d ) {
                // `d` is the original data object for the row
                var html = '<table class="table table-bordered">' +
                    '<thead>' +
                    '<tr>' +
                    '<th colspan="1">&nbsp;</th>' +
                    '<th colspan="3" style="text-align:center">Initial Valuation</th>' +
                    '</tr>' +
                    '<tr>' +
                    '<th style="text-align:center">Lease Currency</th>' +
                    '<th style="text-align:center">Undiscounted Lease Liability</th>'+
                    '<th style="text-align:center">Present Value of Lease Liability</th>'+
                    '<th style="text-align:center">Value of Lease Asset</th>'+
                    '</tr>' +
                    '</thead>' +
                    '<tbody>'+
                    '<tr>' +
                    '<td style="text-align:center">'+ d.initial_lease_currency+'</td>' +
                    '<td style="text-align:center">'+d.initial_undiscounted_lease_liability+'</td>'+
                    '<td style="text-align:center">'+d.initial_present_value_of_lease_liability+'</td>'+
                    '<td style="text-align:center">'+d.initial_value_of_lease_asset+'</td>'+
                    '</tr>' +
                    '</tbody>' +
                    '</table>';

                if(d.has_subsequent_modifications){
                    html += '<table class="table table-bordered">' +
                        '<thead>' +
                        '<tr>' +
                        '<th colspan="3" style="text-align:center">Subsequent Valuation</th>' +
                        '</tr>' +
                        '<tr>' +
                        '<th style="text-align:center">Undiscounted Lease Liability</th>'+
                        '<th style="text-align:center">Present Value of Lease Liability</th>'+
                        '<th style="text-align:center">Value of Lease Asset</th>'+
                        '</tr>' +
                        '</thead>' +
                        '<tbody>'+
                        '<tr>' +
                        '<td style="text-align:center">'+ d.lease_select_low_value.undiscounted_lease_payment+'</td>' +
                        '<td style="text-align:center">'+d.lease_liablity_value+'</td>'+
                        '<td style="text-align:center">'+d.value_of_lease_asset+'</td>'+
                        '</tr>' +
                        '</tbody>' +
                        '</table>';
                }

                return html;
            }
        });
    </script>
@endsection
