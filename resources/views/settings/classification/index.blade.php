@extends('layouts.app')
@section('header-styles')
    <!-- BEGIN CSS for this page -->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/plugins/datatables/dataTables.bootstrap4.min.css') }}"/>
    <!-- END CSS for this page -->
@endsection
@section('content')
        <div class="panel panel-default">
            <div class="panel-heading">Settings | Lease Classifications Settings</div>

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

                @include('settings._menubar')

                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane active">
                        <div class="panel panel-info">
                            <div class="panel-heading">Percentage Rate Types</div>
                            <div class="panel-body">
                                <table class="table table-condensed">
                                    <thead>
                                        <tr>
                                            <th>Sr No.</th>
                                            <th>Title</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($rates as $key =>$rate)
                                            <tr>
                                                <td>{{ $key + 1 }}</td>
                                                <td>{{ $rate->title }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="panel panel-info">
                            <div class="panel-heading">Lease Contract Classification</div>
                            <div class="panel-body">
                                <table class="table table-condensed">
                                    <thead>
                                    <tr>
                                        <th>Sr No.</th>
                                        <th>Title</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($contract_classifications as $key =>$classification)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $classification->title }}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="panel panel-info">
                            <div class="panel-heading">Use of Lease Asset</div>
                            <div class="panel-body">
                                <table class="table table-condensed">
                                    <thead>
                                    <tr>
                                        <th>Sr No.</th>
                                        <th>Title</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($lease_asset_use as $key =>$value)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $value->title }}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="panel panel-info">
                            <div class="panel-heading">Components of Lease Payments</div>
                            <div class="panel-body">
                                <table class="table table-condensed">
                                    <thead>
                                    <tr>
                                        <th>Sr No.</th>
                                        <th>Title</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($lease_payment_component as $key =>$value)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $value->title }}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="panel panel-info">
                            <div class="panel-heading">Nature of Lease Payments</div>
                            <div class="panel-body">
                                <table class="table table-condensed">
                                    <thead>
                                    <tr>
                                        <th>Sr No.</th>
                                        <th>Title</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($lease_payment_nature as $key =>$value)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $value->title }}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>


                        <div class="panel panel-info">
                            <div class="panel-heading">
                                Basis of Variable Lease Payment
                                <span>
                                    <a href="javascript:void(0);" class="btn btn-sm btn-primary pull-right add_more" data-form="add_more_form_lease_basis">Add More</a>
                                </span>
                            </div>
                            <div class="panel-body">
                                <table class="table table-condensed lease_payment_basis_table">
                                    <thead>
                                    <tr>
                                        <th>Sr No.</th>
                                        <th>Title</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($lease_payment_basis as $key =>$value)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td class="title">
                                                    {{ $value->title }}
                                            </td>
                                            <td>

                                                <a data-href="{{ route('settings.leaseclassification.editeasepaymentbasis', ['id' => $value->id]) }}" href="javascript:;" class="btn btn-sm btn-success edit_table_setting">
                                                    <i class="fa fa-pencil-square-o"></i>
                                                </a>

                                                <a href="javascript:;" class="btn btn-sm btn-danger"><i class="fa fa-trash-o"></i></a>
                                            </td>
                                        </tr>
                                    @endforeach
                                        <tr style=" {{ $errors->has('lease_basis_title') ? ' has-error' : 'display: none' }}" class="add_more_form_lease_basis">
                                            <td>{{ count($lease_payment_basis) + 1 }}</td>
                                            <td>
                                                <form action="{{ route('settings.leaseclassification.addleasepaymentbasis') }}" method="POST" class="add_more_form_lease_basis_form">
                                                    {{ csrf_field() }}
                                                    <div class="form-group{{ $errors->has('lease_basis_title') ? ' has-error' : '' }}">
                                                        <input type="text" value="{{ old('lease_basis_title') }}" name="lease_basis_title" placeholder="Title" class="form-control {{ $errors->has('lease_basis_title') ? ' has-error' : '' }}"/>
                                                        @if ($errors->has('lease_basis_title'))
                                                            <span class="help-block">
                                                                <strong>{{ $errors->first('lease_basis_title') }}</strong>
                                                            </span>
                                                        @endif
                                                    </div>
                                                </form>
                                            </td>
                                            <td>
                                                <button type="button" onclick="javascript:$('.add_more_form_lease_basis_form').submit();" class="btn btn-sm btn-success">Save</button>
                                                <a href="javascript:;" class="btn btn-sm btn-danger add_more" data-form="add_more_form_lease_basis">Cancel</a>
                                            </td>
                                        </tr>
                                </table>
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

@endsection