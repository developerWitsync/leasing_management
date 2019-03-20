@extends('layouts.app')
@section('header-styles')
    <link rel="stylesheet" href="{{ asset('css/jquery-ui.css') }}">
    <!-- BEGIN CSS for this page -->
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
        {{--<div class="panel-heading">Drafts</div>--}}

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
                                <strong>Lease Drafts </strong> |
                                <small>Drafts contains all the incomplete leases that has been created. If a lease is listed in the drafts this means that the lease has not been submitted till now and making changes to the lease are allowed.</small>
                            </div>
                            <div class="panel-body">
                                <div class="panel-body frmOuterBx">
                                    <table class="table table-condensed drafts_table">
                                        <thead>
                                        <tr>
                                            <th>Sr No.</th>
                                            <th>Lessor Name</th>
                                            <th>Asset Name</th>
                                            <th>Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>
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
    <script src="{{ asset('assets/plugins/datatables/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables/dataTables.bootstrap4.min.js') }}"></script>

    <script>
        $(document).ready(function () {

            var drafts_table = $('.drafts_table').DataTable({
                responsive: true,
                "columns": [
                    {
                        "data": "id",
                        render: function (data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        }
                    },
                    { "data": "lessor_name"},
                    {
                        "data": "id", render: function (data, type, row, meta) {
                            return row.assets[0]['name'];
                        },
                        sortable : false
                    },
                    { "data": "id"}
                ],
                "columnDefs": [
                    {
                        "targets" : 3,
                        "data" : null,
                        "orderable": false,
                        "className" : "text-center",
                        "render" : function(data, type, full, meta) {
                            var html = "<button  data-toggle='tooltip' data-placement='top' title='Edit Lease Details' type=\"button\" data-lease_id='"+full['id']+"' class=\"btn btn-sm  btn-success edit_lease_detail\"><i class=\"fa fa-pencil-square-o fa-lg\"></i></button><button  data-toggle='tooltip' data-placement='top' title='Delete Lease Details' type=\"button\" data-lease_id='"+full['id']+"' class=\"btn btn-sm btn-danger delete_lease_detail\"><i class=\"fa fa-trash-o\"></i></button>";
                            return html;
                        }
                    }
                ],
                "processing": true,
                "serverSide": true,
                "ajax": "{{ route('drafts.fetchleasedetails')}}"

            });

            $(document.body).on('click', '.edit_lease_detail', function(){
                var lease_id = $(this).data('lease_id');
                window.location.href = '/lease/lessor-details/create/'+lease_id;
            });

         $(document.body).on('click', '.delete_lease_detail', function(){
            var category_id = $(this).data('lease_id');
            bootbox.confirm({
                message: "Are you sure that you want to delete this? These changes cannot be reverted.",
                buttons: {
                    confirm: {
                        label: 'Yes',
                        className: 'btn btn-success'
                    },
                    cancel: {
                        label: 'No',
                        className: 'btn btn-danger'
                    }
                },
                callback: function (result) {
                    if(result) {
                        $.ajax({
                            url : "/drafts/delete-lease-details/"+category_id,
                            type : 'delete',
                            dataType : 'json',
                            success : function (response) {
                                if(response['status']) {
                                    drafts_table.ajax.reload();
                                }
                            }
                        })
                    }
                }
            });
        });
            
        });

    </script>

@endsection