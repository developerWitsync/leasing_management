@extends('layouts.app')
@section('header-styles')
    <!-- BEGIN CSS for this page -->
    <link rel="stylesheet" href="{{ asset('css/jquery-ui.css') }}">
 <link rel="stylesheet" type="text/css" href="{{ asset('assets/plugins/datatables/dataTables.bootstrap4.min.css') }}"/>
    <style>
        td.details-control {
            background: url('{{ asset('assets/plugins/datatables/img/details_open.png') }}') no-repeat center center;
            cursor: pointer;
        }
        tr.shown td.details-control {
            background: url(' {{ asset("assets/plugins/datatables/img/details_close.png") }}') no-repeat center center;
        }
    </style>
    <!-- END CSS for this page -->
@endsection
@section('content')
 <div class="panel panel-default">
        <div class="panel-heading">Lease Valuation</div>
        <div class="itemTab">
            <ul>
              
            <li> 
               <button type="submit" class="list-group-item @if(request()->segment('2') == 'capitalized') active @endif" onclick="location.href='{{ route('leasevaluation.index') }}'">Capitalized Lease Asset</button></a>
              </li>
            <li>
             <button type="submit" class="list-group-item @if(request()->segment('2') == 'noncapitalized') active @endif" onclick="location.href='{{ route('leasevaluation.noncapitalized') }}'">Non-Capitalized Lease Asset</a></button>
            </li>
        </ul>
        </div>

        <div class="panel-body">

            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            <div class="tab-content" style="padding: 0px;">
                <div role="tabpanel" class="tab-pane active">
                        <div class="panel panel-info">
                            <div class="panel-heading">
                              Lease Valutaion
                            </div>
                             @include('leasevaluation._capitalizedmenubar')
                      
                            <div class="panel-body">
                                <div class="panel-body frmOuterBx">
                                     <div class="panel-heading">Section A: Leases for own use lease</div>
                                    <table class="table table-condensed modify_table">
                                        <thead>
                                        <tr>
                                            <th>Serial Number </th>
                                            <th>Lease Contract Reference Number </th>
                                            <th>Lessor Name</th>
                                            <th>Underlying Lease Asset Name</th>
                                            <th>Remaining Lease Term</th>
                                            <th>Date</th>
                                            <th>Discount Rate</th>
                                            <th>Undiscounted Lease Liability</th>
                                            <th>Present Value of Lease Liability</th>
                                            <th>Value of Lease Asset</th>
                                            <th>Undiscounted Lease Liability</th>
                                            <th>Present Value of Lease Liability</th>
                                            <th>Value of Lease Asset</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                @foreach($own_assets_capitalized as $key=>$asset)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td style="width: 10%">
                                        {{ $asset->uuid}}
                                    </td>
                                    <td>
                                        {{ $asset->lease->lessor_name }}
                                    </td>
                                    <td>
                                        {{ $asset->name}}
                                    </td>
                                    <td>
                                        {{ $asset->lease_term}}
                                    </td>
                                    <td>
                                        {{ date('d-m-Y',strtotime($asset->lease_start_date)) }}
                                    </td>
                                     @if(isset($asset->leaseSelectDiscountRate->discount_rate_to_use))
                                    <td>
                                        {{ $asset->leaseSelectDiscountRate->discount_rate_to_use }}
                                    </td>
                                @else
                                    <td>-</td>
                                @endif
                                    <td>{{$asset->payments[0]->total_amount_per_interval }}</td>
                                   <td>
                                        {{ $asset->lease_liablity_value }}
                                    </td>
                                    <td>
                                        {{ $asset->value_of_lease_asset }}
                                    </td>
                                     <td>
                                        {{ $asset->lease_liablity_value }}
                                    </td>
                                   <td>
                                        {{ $asset->lease_liablity_value }}
                                    </td>
                                    <td>
                                        {{ $asset->value_of_lease_asset }}
                                    </td>
                                </tr>
                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="panel-body">
                                <div class="panel-body frmOuterBx">
                                     <div class="panel-heading">Section B: Leases for Sub Lease Purpose</div>
                                    <table class="table table-condensed modify_table">
                                        <thead>
                                            <tr>
                                            <th>Serial Number </th>
                                            <th>Lease Contract Reference Number </th>
                                            <th>Lessor Name</th>
                                            <th>Underlying Lease Asset Name</th>
                                            <th>Remaining Lease Term</th>
                                            <th>Date</th>
                                            <th>Discount Rate</th>
                                            <th>Undiscounted Lease Liability</th>
                                            <th>Present Value of Lease Liability</th>
                                            <th>Value of Lease Asset</th>
                                            <th>Undiscounted Lease Liability</th>
                                            <th>Present Value of Lease Liability</th>
                                            <th>Value of Lease Asset</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                @foreach($sublease_assets_capitalized as $key=>$asset)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td style="width: 10%">
                                        {{ $asset->uuid}}
                                    </td>
                                    <td>
                                        {{ $asset->lease->lessor_name }}
                                    </td>
                                    <td>
                                        {{ $asset->name}}
                                    </td>
                                    <td>
                                        {{ $asset->lease_term}}
                                    </td>
                                    <td>
                                        {{ date('d-m-Y',strtotime($asset->lease_start_date)) }}
                                    </td>
                                    @if(isset($asset->leaseSelectDiscountRate->discount_rate_to_use))
                                    <td>
                                        {{ $asset->leaseSelectDiscountRate->discount_rate_to_use }}
                                    </td>
                                @else
                                    <td>-</td>
                                @endif
                                    <td>{{$asset->payments[0]->total_amount_per_interval }}</td>
                                   <td>
                                        {{ $asset->lease_liablity_value }}
                                    </td>
                                    <td>
                                        {{ $asset->value_of_lease_asset }}
                                    </td>
                                     <td>
                                        {{ $asset->lease_liablity_value }}
                                    </td>
                                   <td>
                                        {{ $asset->lease_liablity_value }}
                                    </td>
                                    <td>
                                        {{ $asset->value_of_lease_asset }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                </div>
            </div>
 </div>
</div>
@endsection
@section('footer-script')
    <script src="{{ asset('assets/plugins/bootbox/bootbox.min.js') }}"></script>
    <script src="{{ asset('js/jquery-ui.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.16/js/dataTables.bootstrap4.min.js"></script>

   <!--  <script>
        $(document).ready(function () {

            var drafts_table = $('.modify_table').DataTable({
                responsive: true,
                "columns": [
                    {
                        "data": "id",
                        render: function (data, type, row, meta) {
                            
           
                            return meta.row + meta.settings._iDisplayStart + 1;
                        }
                    },
                    { "data": "lessor_name"},
                    { "data": "total_assets"},
                    { "data": "lease_type.title"},
                     { "data": "id"}
                ],
                "columnDefs": [
                    {
                        "targets" : 4,
                        "data" : null,
                        "orderable": false,
                        "className" : "text-center",
                        "render" : function(data, type, full, meta) {
                            var html = "<button  data-toggle='tooltip' data-placement='top' title='Edit Lease Details' type=\"button\" data-lease_id='"+full['id']+"' class=\"btn btn-sm  btn-success modify_lease_detail\">Modify Lease</button><a href=\"javascript:;\"";
                            return html;
                        }
                    }
                ],
                "processing": true,
                "serverSide": true,
                "ajax": "{{ route('modifylease.fetchleasedetails')}}"

            });

            $(document.body).on('click', '.modify_lease_detail', function(){
                var lease_id = $(this).data('lease_id');
                window.location.href = '/modify-lease/create/'+lease_id;
            });

             
            
        });

    </script>
 -->
@endsection
