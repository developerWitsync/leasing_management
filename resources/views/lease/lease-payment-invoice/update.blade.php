@extends('layouts.app')
@section('header-styles')
    <!-- BEGIN CSS for this page -->
    <link rel="stylesheet" href="{{ asset('css/jquery-ui.css') }}">
    <!-- END CSS for this page -->
@endsection
@section('content')
    <div class="panel panel-default">
        <div class="panel-heading">Add New Lease | Lease Payment Invoice from Lessor</div>

        <div class="panel-body">
            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach (array_unique($errors->all()) as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form role="form" class="form-horizontal" enctype="multipart/form-data" method="POST" action="{{ route('addlease.leasepaymentinvoice.update', ['id' => $lease->id]) }}">
    {{ csrf_field() }}

    <div class="form-group{{ $errors->has('lease_payment_invoice_received') ? ' has-error' : '' }} required">
        <label for="name" class="col-md-4 control-label">Any Lease Payment Invoice Received from Lessor</label>
        <div class="col-md-6 form-check form-check-inline" required>
            <input class="form-check-input" name="lease_payment_invoice_received" id="yes" type="checkbox" value = "yes" @if(old('lease_payment_invoice_received', $model->lease_payment_invoice_received) == "yes") checked="checked" @endif>
            <label class="form-check-label" for="yes" id="yes" style="vertical-align: 4px">Yes</label><br>
            <input class="form-check-input" name="lease_payment_invoice_received" id="no" type="checkbox" value = "no" @if(old('lease_payment_invoice_received', $model->lease_payment_invoice_received)  == "no") checked="checked" @endif>
            <label class="form-check-label" for="no" id="no" style="vertical-align: 4px">No</label>
            @if ($errors->has('lease_payment_invoice_received'))
                <span class="help-block">
                        <strong>{{ $errors->first('lease_payment_invoice_received') }}</strong>
                    </span>
            @endif
        </div>
    </div>

    <div class="form-group">
        <div class="col-md-6 col-md-offset-4">

            <a href="{{ route('addlease.leaseincentives.index', ['id' => $lease->id]) }}" class="btn btn-danger">Cancel</a>
            <button type="submit" class="btn btn-success">
                Submit
            </button>
            @if($lease->leaseInvoice)
            <a href="{{ route('addlease.reviewsubmit.index', ['id' => $lease->id]) }}" class="btn btn-primary">Next</a>
             @endif
        </div>
    </div>

</form>
        </div>
    </div>
@endsection

@section('footer-script')
    <script type="text/javascript">
        $(document).on('click', 'input[type="checkbox"]', function() {
            $('input[type="checkbox"]').not(this).prop('checked', false);
        });
    </script>
@endsection
