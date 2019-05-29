<form role="form" class="form-horizontal" method="post" enctype="multipart/form-data" id="lease_select">
    {{ csrf_field() }}
    <div class="categoriesOuter clearfix">

        <div class="form-group required">
            <label for="asset_name" class="col-md-12 control-label">Lease Asset Name</label>
            <div class="col-md-12 form-check form-check-inline">
                <input type="text" value="{{ $asset->name}}" class="form-control" id="asset_name" name="asset_name"
                       disabled="disabled">
            </div>
        </div>

        <div class="form-group required">
            <label for="asset_category" class="col-md-12 control-label">Lease Asset Classification</label>
            <div class="col-md-12 form-check form-check-inline">
                <input type="text" value="{{ $asset->category->title}}" class="form-control" id="asset_category"
                       name="asset_category" disabled="disabled">
            </div>
        </div>

        <div class="form-group{{ $errors->has('interest_rate') ? ' has-error' : '' }} ">
            <label for="name" class="col-md-12 control-label">Interest Rate Implicit in the Lease</label>
            <div class="col-md-12 form-check form-check-inline" required>
                <input class="form-control" name="interest_rate" id="yes" type="text"
                       value="{{ old('interest_rate', $model->interest_rate) }}">
                @if ($errors->has('interest_rate'))
                    <span class="help-block">
                    <strong>{{ $errors->first('interest_rate') }}</strong>
                </span>
                @endif
            </div>
        </div>
        <div class="form-group{{ $errors->has('annual_average_esclation_rate') ? ' has-error' : '' }} ">
            <label for="name" class="col-md-12 control-label">Annual Average Escalation Rate</label>
            <div class="col-md-12 form-check form-check-inline" required>
                <input class="form-control" name="annual_average_esclation_rate" id="yes" type="text"
                       value="{{ old('annual_average_esclation_rate', $model->annual_average_esclation_rate) }}">
                @if ($errors->has('annual_average_esclation_rate'))
                    <span class="help-block">
                    <strong>{{ $errors->first('annual_average_esclation_rate') }}</strong>
                </span>
                @endif
            </div>
        </div>
        <div class="form-group{{ $errors->has('discount_rate_to_use') ? ' has-error' : '' }} required">
            @if($settings->date_of_initial_application == 2 && !$subsequent_modify_required)
                <label for="name" class="col-md-12 control-label">Input Historical Average Discount Rate(in %)</label>
            @else
                <label for="name" class="col-md-12 control-label">Select Discount Rate to Use(in %)</label>
            @endif

            <div class="col-md-12 form-check form-check-inline" required>
                <input class="form-control" name="discount_rate_to_use" id="discount_rate_to_use" type="text"
                       value="{{ old('discount_rate_to_use', $model->discount_rate_to_use) }}">
                @if ($errors->has('discount_rate_to_use'))
                    <span class="help-block">
                        <strong>{{ $errors->first('discount_rate_to_use') }}</strong>
                    </span>
                @endif
            </div>
        </div>

        <div class="form-group{{ $errors->has('daily_discount_rate') ? ' has-error' : '' }} required">
            <label for="name" class="col-md-12 control-label">Effective Daily Discount Rate(in %)</label>
            <div class="col-md-12 form-check form-check-inline" required>
                <input class="form-control" name="daily_discount_rate" id="daily_discount_rate" type="text"
                       value="{{ old('daily_discount_rate', $model->daily_discount_rate) }}" readonly="readonly">
                @if ($errors->has('daily_discount_rate'))
                    <span class="help-block">
                    <strong>{{ $errors->first('daily_discount_rate') }}</strong>
                </span>
                @endif
            </div>
        </div>

    </div>
    <div class="form-group btnMainBx">
        <div class="col-md-4 col-sm-4 btn-backnextBx">
            <a href="{{ route('addlease.lowvalue.index', ['id' => $lease->id]) }}" class="btn btn-danger"><i
                        class="fa fa-arrow-left"></i> {{ env('BACK_LABEL')}}</a>
        </div>
        <div class="col-md-4 col-sm-4 btnsubmitBx aligncenter">
            <button type="submit" class="btn btn-success">
                {{ env('SAVE_LABEL') }} <i class="fa fa-download"></i></button>
        </div>
        <div class="col-md-4 col-sm-4 btn-backnextBx rightlign ">
            <input type="hidden" name="action" value="">
            <a href="javascript:void(0);" class="btn btn-primary save_next"> {{ env('NEXT_LABEL') }} <i
                        class="fa fa-arrow-right"></i></a>
        </div>
    </div>

</form>

@section('footer-script')
    <script src="{{ asset('js/jquery-ui.js') }}"></script>
    <script type="text/javascript">

        $(function(){
            $('#discount_rate_to_use').focusout( function(){
                var value = $(this).val();
                if($.isNumeric(value)){
                        var effective_daialy_disount_rate  = (Math.pow(1 + (value/100), (1/365)) - 1) * 100;
                        $("#daily_discount_rate").val(effective_daialy_disount_rate);
                } else {
                    alert("Please enter numbers only.");
                }
            })
        })

        $('.save_next').on('click', function (e) {
            e.preventDefault();
            $('input[name="action"]').val('next');
            $('#lease_select').submit();
        });
    </script>
@endsection