@extends('layouts.app')
@section('header-styles')
    <!-- BEGIN CSS for this page -->
    <link rel="stylesheet" type="text/css"
          href="{{ asset('assets/plugins/datatables/dataTables.bootstrap4.min.css') }}"/>
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
        {{--<div class="panel-heading">Lease Assets Settings</div>--}}

        <div class="panel-body">
            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif

            @include('settings._menubar')

            <div class="">
                <div role="tabpanel" class="tab-pane active">
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            Expected Useful Life of Asset
                            <span>
                                    <a href="javascript:void(0);" class="btn btn-sm btn-primary pull-right add_more"
                                       data-form="add_more_expected_life_of_assets">Add More</a>
                                </span>
                        </div>
                        <div class="panel-body settingTble">
                            <table class="table table-condensed">
                                <thead>
                                <tr>
                                    <th>Sr No.</th>
                                    <th>Useful Life In Years</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($expected_life_of_assets as $key=> $life_of_asset)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>
                                            {{ $life_of_asset->years < 0 ? 'Indefinite' : $life_of_asset->years }}
                                        </td>
                                        <td>
                                            @if($life_of_asset->years > 0)
                                                <a data-href="{{ route('settings.leaseassets.editlife', ['id' => $life_of_asset->id]) }}"
                                                   href="javascript:;"
                                                   class="btn btn-sm btn-success edit_table_setting">
                                                    <i class="fa fa-pencil-square-o"></i>
                                                </a>
                                                <a data-href="{{ route('settings.leaseassets.deletelife', ['id' => $life_of_asset->id]) }}"
                                                   href="javascript:;" class="btn btn-sm btn-danger delete_settings"><i
                                                            class="fa fa-trash-o"></i></a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach

                                <tr style=" {{ $errors->has('expected_life_number') ? ' has-error' : 'display: none' }}"
                                    class="add_more_expected_life_of_assets">
                                    <td>{{ count($expected_life_of_assets) + 1 }}</td>
                                    <td>
                                        <form action="{{ route('settings.leaseassets.addlife') }}" method="POST"
                                              class="add_more_expected_life_of_assets_form">
                                            {{ csrf_field() }}
                                            <div class="form-group{{ $errors->has('expected_life_number') ? ' has-error' : '' }}">
                                                <input type="text" value="{{ old('expected_life_number') }}"
                                                       name="expected_life_number"
                                                       placeholder="Expected Useful Life In Years"
                                                       class="form-control {{ $errors->has('expected_life_number') ? ' has-error' : '' }}"/>
                                                @if ($errors->has('expected_life_number'))
                                                    <span class="help-block">
                                                                <strong>{{ $errors->first('expected_life_number') }}</strong>
                                                            </span>
                                                @endif
                                            </div>
                                        </form>
                                    </td>
                                    <td>
                                        <button type="button"
                                                onclick="javascript:$('.add_more_expected_life_of_assets_form').submit();"
                                                class="btn btn-sm btn-success" title="Save"><i
                                                    class="fa fa-check-square"></i></button>
                                        <a href="javascript:;" class="btn btn-sm btn-danger add_more"
                                           data-form="add_more_expected_life_of_assets" title="Cancel"><i
                                                    class="fa fa-times"></i></a>
                                    </td>
                                </tr>

                                </tbody>
                            </table>
                        </div>
                    </div>

                    @foreach($lease_assets_categories as $category)
                        @include('settings.leaseassets._category', ['category' => $category])
                    @endforeach

                    {{--Assumptions --}}

                    <div class="panel panel-info">
                        <div class="panel-heading">
                            Assumpitons
                        </div>
                        <div class="panel-body">
                            <div class="panel-body settingTble">
                                <table class="table table-condensed">
                                    <thead>
                                    <tr>
                                        <th>Sr No.</th>
                                        <th>Assumption</th>
                                        <th>Value</th>
                                    </tr>
                                    <tr>
                                        <td>1</td>
                                        <td>Assumption for Providing Straight Line Depreciation</td>
                                        <td>Partial Month Considered as Full Month</td>
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

    <!-- Edit Settings Modal -->
    <div id="myModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">

            </div>
        </div>
    </div>

@endsection
@section('footer-script')
    <script src="{{ asset('assets/plugins/bootbox/bootbox.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables/dataTables.bootstrap4.min.js') }}"></script>


    @foreach($lease_assets_categories as $category)
        <script>
            $(document).ready(function () {
                var table_{{ $category->id }} = $('.{{ str_slug($category->title) }}').DataTable({
                    responsive: true,
                    "columns": [
                        {
                            "data": "id",
                            render: function (data, type, row, meta) {
                                return meta.row + meta.settings._iDisplayStart + 1;
                            }
                        },
                        {"data": "title"},
                        {
                            "data": "id",
                            "render": function (data, type, row, meta) {
                                if ($.isEmptyObject(row.depreciation_method)) {
                                    return "N/A";
                                } else {
                                    return row.depreciation_method.title;
                                }
                            },
                            'sortable': false
                        },
                        {"data": "id"}
                    ],
                    "columnDefs": [
                        {
                            "targets": 3,
                            "data": null,
                            "orderable": false,
                            "className": "text-center",
                            "render": function (data, type, full, meta) {
                                var html = "<button  data-toggle='tooltip' data-placement='top' title='Edit Country' type=\"button\" data-category_id='" + full['id'] + "' class=\"btn btn-sm  btn-success edit_category_setting\"><i class=\"fa fa-pencil-square-o fa-lg\"></i></button>";
                                html += "&nbsp;&nbsp;<button  data-toggle='tooltip' data-placement='top' title='Delete Country' type=\"button\" data-category_id='" + full['id'] + "' class=\"btn btn-sm btn-danger delete_category_setting\">  <i class=\"fa fa-trash-o fa-lg\"></i></button>"
                                return html;
                            }
                        }
                    ],
                    "processing": true,
                    "serverSide": true,
                    "ajax": "{{ route('settings.leaseassets.fetchassetcategorysettings', ['category' => $category->id]) }}"

                });
            });
        </script>
    @endforeach

    <script>
        $(document.body).on('click', '.edit_category_setting', function () {
            var category_id = $(this).data('category_id');
            window.location.href = '/settings/lease-assets/edit-lease-asset-category-setting/' + category_id;
        });

        $(document.body).on('click', '.delete_category_setting', function () {
            var category_id = $(this).data('category_id');
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
                    if (result) {
                        $.ajax({
                            url: "/settings/lease-assets/delete-lease-asset-category-setting/" + category_id,
                            type: 'delete',
                            dataType: 'json',
                            success: function (response) {
                                if (response['status']) {
                                    window.location.reload();
                                }
                            }
                        })
                    }
                }
            });
        });

    </script>

@endsection

