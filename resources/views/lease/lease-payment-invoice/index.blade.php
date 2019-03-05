@extends('layouts.app')
@section('header-styles')
    <!-- BEGIN CSS for this page -->
    <link rel="stylesheet" href="{{ asset('css/jquery-ui.css') }}">
    <!-- END CSS for this page -->
@endsection
@section('content')
    <div class="panel panel-default">
        <div class="panel-heading">Lease Payment Invoice </div>

        <div class="panel-body">
            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif

                @include('lease._subsequent_details')

            <form role="form" class="form-horizontal" id="lease_payment" enctype="multipart/form-data" method="POST" action="{{ route('addlease.leasepaymentinvoice.index', ['id' => $lease->id]) }}">
            {{ csrf_field() }}
            <div class="categoriesOuter clearfix">
                <div class="form-group{{ $errors->has('lease_payment_invoice_received') ? ' has-error' : '' }} required">
                    <label for="name" class="col-md-12 control-label">Any Lease Payment Invoice Received from Lessor</label>
                    <div class="col-md-12 form-check form-check-inline  mrktavail" required>
                        <span>
                            <input class="form-check-input" name="lease_payment_invoice_received" id="yes" type="checkbox" value = "yes" @if(old('lease_payment_invoice_received', $model->lease_payment_invoice_received) == "yes") checked="checked" @endif>
                            <label class="form-check-label" for="yes" id="yes" style="vertical-align: 4px">Yes</label>
                        </span>
                        <span>
                            <input class="form-check-input" name="lease_payment_invoice_received" id="no" type="checkbox" value = "no" @if(old('lease_payment_invoice_received', $model->lease_payment_invoice_received)  == "no") checked="checked" @endif>
                            <label class="form-check-label" for="no" id="no" style="vertical-align: 4px">No</label>
                        </span>
                        @if ($errors->has('lease_payment_invoice_received'))
                            <span class="help-block">
                                    <strong>{{ $errors->first('lease_payment_invoice_received') }}</strong>
                                </span>
                        @endif
                    </div>
                </div>
            </div>
<div class="form-group btnMainBx">
 <div class="col-md-4 col-sm-4 btn-backnextBx">
        <a href="{{ route('addlease.leasevaluation.index', ['id' => $lease->id]) }}" class="btn btn-danger"><i class="fa fa-arrow-left"></i> {{ env('BACK_LABEL')}}</a>
    </div>
    <div class="col-md-4 col-sm-4 btnsubmitBx aligncenter">
        <button type="submit" class="btn btn-success"> 
        {{ env('SAVE_LABEL') }} <i class="fa fa-download"></i></button>
    </div>
    <div class="col-md-4 col-sm-4 btn-backnextBx rightlign ">
        <input type="hidden" name="action" value="">
        <a href="javascript:void(0);" class="btn btn-primary save_next"> {{ env('NEXT_LABEL') }} <i class="fa fa-arrow-right"></i></a>
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
         $('.save_next').on('click', function (e) {
                e.preventDefault();
                $('input[name="action"]').val('next');
                $('#lease_payment').submit();
        });
    </script>
@endsection
