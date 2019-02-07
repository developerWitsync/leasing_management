<form role="form" class="form-horizontal" method="post" enctype="multipart/form-data">
    {{ csrf_field() }}
<div class="categoriesOuter clearfix">
    <div class="form-group required">
        <label for="uuid" class="col-md-12 control-label">ULA Code</label>
        <div class="col-md-12 form-check form-check-inline">
            <input type="text" value="{{ $asset->uuid}}" class="form-control" id="uuid" name="uuid" disabled="disabled">
        </div>
    </div>

    <div class="form-group required">
        <label for="asset_name" class="col-md-12 control-label">Asset Name</label>
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

    <div class="form-group{{ $errors->has('any_residual_value_gurantee') ? ' has-error' : '' }} required">
        <label for="name" class="col-md-12 control-label">Any Residual Guarantee Value</label>
        <div class="col-md-12 form-check form-check-inline mrktavail" required>
            <span>
                <input class="form-check-input" name="any_residual_value_gurantee" id="yes" type="checkbox" value="yes"
                   @if(old('any_residual_value_gurantee', $model->any_residual_value_gurantee) == "yes") checked="checked" @endif>
                <label class="form-check-label" for="yes" id="yes" style="vertical-align: 4px">Yes</label>
            </span>
            <span><input class="form-check-input" name="any_residual_value_gurantee" id="no" type="checkbox" value="no"
                   @if(old('any_residual_value_gurantee', $model->any_residual_value_gurantee)  == "no") checked="checked" @endif>
                <label class="form-check-label" for="no" id="no" style="vertical-align: 4px">No</label>
            </span>
            @if ($errors->has('any_residual_value_gurantee'))
                <span class="help-block">
                        <strong>{{ $errors->first('any_residual_value_gurantee') }}</strong>
                    </span>
            @endif
        </div>
    </div>


    <div class="hidden-group hidden-fields gg {{$model->any_residual_value_gurantee}}"
         @if(old('any_residual_value_gurantee',$model->any_residual_value_gurantee ) == "yes") style="display:block;"
         @else  style="display:none;" @endif>
        <div class="form-group{{ $errors->has('lease_payemnt_nature_id') ? ' has-error' : '' }} required">
            <label for="lease_payemnt_nature_id" class="col-md-12 control-label">Nature of Lease Payment</label>
            <div class="col-md-12 form-check form-check-inline">
                <select name="lease_payemnt_nature_id" id="lease_payemnt_nature_id" class="form-control">
                    <option value="">--Select Lease Payment Nature--</option>
                    @foreach($lease_payments_nature as $nature)
                        <option value="{{ $nature->id}}"
                                @if(old('lease_payemnt_nature_id', $model->lease_payemnt_nature_id) == $nature->id) selected="selected" @endif>{{ $nature->title }}</option>
                    @endforeach
                </select>
                @if ($errors->has('lease_payemnt_nature_id'))
                    <span class="help-block">
                        <strong>{{ $errors->first('lease_payemnt_nature_id') }}</strong>
                    </span>
                @endif
            </div>
        </div>
    </div>

    <div class="form-group{{ $errors->has('variable_basis_id') ? ' has-error' : '' }} required hidden-fields amount_determinable"
         @if(old('any_residual_value_gurantee', $model->any_residual_value_gurantee) == 'yes' && old('lease_payemnt_nature_id', $model->lease_payemnt_nature_id) == '2') @else style="display: none" @endif>
        <div class="{{ $errors->has('variable_basis_id') ? ' has-error' : '' }} required">
            <label for="variable_basis_id" class="col-md-12 control-label">Variable Basis</label>
            <div class="col-md-12 form-check form-check-inline">
                <select name="variable_basis_id" id="variable_basis_id" class="form-control">
                    <option value="">--Select Variable Basis--</option>
                    @foreach($payment_lease_basis as $basis)
                        <option value="{{ $basis->id}}"
                                @if(old('variable_basis_id',$model->variable_basis_id ) == $basis->id) selected="selected" @endif>{{ $basis->title }}</option>
                    @endforeach
                </select>
                @if ($errors->has('variable_basis_id'))
                    <span class="help-block">
                        <strong>{{ $errors->first('variable_basis_id') }}</strong>
                    </span>
                @endif
            </div>
        </div>
    </div>

    <div class="form-group{{ $errors->has('amount_determinable') ? ' has-error' : '' }} required hidden-fields amount_determinable"
         @if(old('any_residual_value_gurantee',$model->any_residual_value_gurantee ) == 'yes' && old('lease_payemnt_nature_id', $model->lease_payemnt_nature_id) == '2') @else style="display: none" @endif>
        <label for="type" class="col-md-12 control-label">Amount Determinable</label>
        <div class="col-md-12 mrktavail">
            <span><input class="form-check-input" name="amount_determinable" type="checkbox" id="amount_determinable_yes"
                   value="yes" @if(old('amount_determinable', $model->amount_determinable)  == "yes") checked="checked" @endif >
                   <label class="form-check-label" for="amount_determinable_yes" style="vertical-align: 4px">Yes</label>
            </span>
            <span>
                <input class="form-check-input" name="amount_determinable" type="checkbox" id="amount_determinable_no"
                   value="no" @if(old('amount_determinable', $model->amount_determinable)  == "no") checked="checked" @endif>
                <label class="form-check-label" for="amount_determinable_no" style="vertical-align: 4px">No</label>
            </span>
            @if ($errors->has('amount_determinable'))
                <span class="help-block">
                        <strong>{{ $errors->first('amount_determinable') }}</strong>
                    </span>
            @endif
        </div>
    </div>

    <div class="hidden-group hidden-fields gg {{$model->any_residual_value_gurantee}}"
         @if(old('any_residual_value_gurantee',$model->any_residual_value_gurantee ) == "yes") style="display:block;"
         @else  style="display:none;" @endif>
        <div class="form-group{{ $errors->has('currency') ? ' has-error' : '' }} required">
            <label for="currency" class="col-md-12 control-label">Currency</label>
            <div class="col-md-12 form-check form-check-inline">
                <input type="text" value="{{ $lease->lease_contract_id }}" class="form-control" id="currency"
                       name="currency" readonly="readonly">
                @if ($errors->has('currency'))
                    <span class="help-block">
                        <strong>{{ $errors->first('currency') }}</strong>
                    </span>
                @endif
            </div>
        </div>


        <div class="form-group{{ $errors->has('similar_asset_items') ? ' has-error' : '' }} required">
            <label for="similar_asset_items" class="col-md-12 control-label">Number of Units of Lease Assets of Similar
                Characteristics</label>
            <div class="col-md-12 form-check form-check-inline">
                <input type="text" value="{{ $asset->similar_asset_items }}" class="form-control"
                       id="similar_asset_items" name="similar_asset_items" readonly="readonly">
                @if ($errors->has('similar_asset_items'))
                    <span class="help-block">
                        <strong>{{ $errors->first('similar_asset_items') }}</strong>
                    </span>
                @endif
            </div>
        </div>
        <div class="form-group{{ $errors->has('residual_gurantee_value') ? ' has-error' : '' }} required">
            <label for="residual_gurantee_value" class="col-md-12 control-label">Residual Guarantee Value Per
                Unit</label>
            <div class="col-md-12">
                <input type="text" placeholder="Residual Gurantee Value" class="form-control"
                       id="residual_gurantee_value" name="residual_gurantee_value"
                       value="{{ old('residual_gurantee_value', $model->residual_gurantee_value) }}">
                @if ($errors->has('residual_gurantee_value'))
                    <span class="help-block">
                        <strong>{{ $errors->first('residual_gurantee_value') }}</strong>
                    </span>
                @endif
            </div>
        </div>

        <div class="form-group{{ $errors->has('total_residual_gurantee_value') ? ' has-error' : '' }} required">
            <label for="total_residual_gurantee_value" class="col-md-12 control-label">Total Residual Guarantee
                Value</label>
            <div class="col-md-12">
                <input type="text" name="total_residual_gurantee_value" for="type" class="form-control"
                       id="total_residual_gurantee_value"
                       value="{{ old('total_residual_gurantee_value', $model->total_residual_gurantee_value) }}">
                @if ($errors->has('total_residual_gurantee_value'))
                    <span class="help-block">
                        <strong>{{ $errors->first('total_residual_gurantee_value') }}</strong>
                    </span>
                @endif
            </div>
        </div>

    </div>

    <div class="textareaOuter form-group{{ $errors->has('other_desc') ? ' has-error' : '' }}">
        <label for="other_desc" class="col-md-12 control-label">Other Description</label>
        <div class="col-md-12">
            <textarea class="form-control txtareaInp" name="other_desc">{{ old('other_desc', $model->other_desc) }}</textarea>
            @if ($errors->has('total_residual_gurantee_value'))
                <span class="help-block">
                        <strong>{{ $errors->first('total_residual_gurantee_value') }}</strong>
                    </span>
            @endif
        </div>
    </div>
</div>

    <div class="form-group btnMainBx">
        <div class="col-md-6 col-sm-6 btn-backnextBx">

            <a href="{{ route('addlease.fairmarketvalue.index', ['id' => $lease->id]) }}"
               class="btn btn-danger">Back</a>
            @if($asset->residualGuranteeValue)
                <a href="{{ route('addlease.durationclassified.index', ['id' => $lease->id]) }}"
                   class="btn btn-primary">Next</a>
            @endif

        </div>
        <div class="col-md-6 col-sm-6 btnsubmitBx">

            <button type="submit" class="btn btn-success">
                Save
            </button>
        </div>
    </div>

</form>

@section('footer-script')
    <script src="{{ asset('assets/plugins/bootbox/bootbox.min.js') }}"></script>
    <script>
        $(function () {

            $("#lease_payemnt_nature_id").on('change', function () {
                var value = $(this).val();
                if (value == '2') {
                    $('.amount_determinable').show();
                } else {
                    $('.amount_determinable').hide();
                }
            });
        });
    </script>
    <script type="text/javascript">
        $(document).on('click', 'input[name="any_residual_value_gurantee"]', function () {
            $('input[name="any_residual_value_gurantee"]').not(this).prop('checked', false);

            if ($(this).is(':checked') && $(this).val() == 'yes') {
                $('.hidden-fields').show();
            } else {
                $('.hidden-fields').hide();
            }


        });

        $(document).on('click', 'input[name="amount_determinable"]', function () {
            $('input[name="amount_determinable"]').not(this).prop('checked', false);

            if ($(this).is(':checked') && $(this).val() == 'yes') {
                $("#amount_determinable_no").prop('checked', false);
            } else {
                $("#amount_determinable_yes").prop('checked', false);
            }


        });

        jQuery(document).ready(function ($) {
            var $total = $('#total_residual_gurantee_value'),
                $value = $('#residual_gurantee_value');
            $units = $("#similar_asset_items").val();
            $value.on('input', function (e) {
                var total = 0;
                $value.each(function (index, elem) {
                    if (!Number.isNaN(parseInt(this.value, 10)))
                        total = $units * parseInt(this.value, 10);
                });
                $total.val(total);
            });


            $('#workings_doc').change(function () {
                $('#workings_doc').show();
                var filename = $('#workings_doc').val();
                var or_name = filename.split("\\");
                $('#upload2').val(or_name[or_name.length - 1]);
            });

        });
    </script>

@endsection