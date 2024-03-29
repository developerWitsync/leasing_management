<form role="form" class="form-horizontal" method="post" enctype="multipart/form-data" id="lease_fair">
    {{ csrf_field() }}
    <div class="categoriesOuter clearfix">
        

        <div class="form-group required">
            <label for="asset_name" class="col-md-12 control-label">Lease Asset Name</label>
            <div class="col-md-12 form-check form-check-inline">
                <input type="text" value="{{ $asset->name}}" class="form-control" id="asset_name" name="asset_name" disabled="disabled">
            </div>
        </div>

        <div class="form-group required">
            <label for="asset_category" class="col-md-12 control-label">Lease Asset Classification</label>
            <div class="col-md-12 form-check form-check-inline">
                <input type="text" value="{{ $asset->category->title}}" class="form-control" id="asset_category" name="asset_category" disabled="disabled">
            </div>
        </div>


        <div class="form-group{{ $errors->has('is_market_value_present') ? ' has-error' : '' }} required">
            <label for="name" class="col-md-12 control-label">Is Market Value Available</label>
            <div class="col-md-12 form-check form-check-inline mrktavail " required>
                <span><input class="form-check-input" name="is_market_value_present" id="yes" type="checkbox" value="yes" @if(old('is_market_value_present', $model->is_market_value_present) == "yes") checked="checked" @endif>
                <label class="form-check-label" for="yes" id="yes" style="vertical-align: 4px">Yes</label></span>
                <span><input class="form-check-input" name="is_market_value_present" id="no" type="checkbox" value="no" @if(old('is_market_value_present', $model->is_market_value_present)  == "no") checked="checked" @endif>
                <label class="form-check-label" for="no" id="no" style="vertical-align: 4px">No</label></span>
                @if ($errors->has('is_market_value_present'))
                    <span class="help-block">
                            <strong>{{ $errors->first('is_market_value_present') }}</strong>
                        </span>
                @endif
            </div>
        </div>

        <div class="hidden-group" id="hidden-fields" @if(old('is_market_value_present',$model->is_market_value_present ) == "yes") style="display:block;" @else  style="display:none;" @endif>
            <div class="form-group{{ $errors->has('currency') ? ' has-error' : '' }} required">
                <label for="currency" class="col-md-12 control-label">Currency</label>
                <div class="col-md-12 form-check form-check-inline">
                    <input type="text" value="{{ $lease->lease_contract_id }}" class="form-control" id="currency" name="currency" readonly="readonly">
                    @if ($errors->has('currency'))
                        <span class="help-block">
                            <strong>{{ $errors->first('currency') }}</strong>
                        </span>
                    @endif
                </div>
            </div>


            <div class="form-group{{ $errors->has('similar_asset_items') ? ' has-error' : '' }} required">
                <label for="similar_asset_items" class="col-md-12 control-label">Number of Units of Lease Assets of Similar Characteristics</label>
                <div class="col-md-12 form-check form-check-inline">
                    <input type="text" value="{{ $asset->similar_asset_items }}" class="form-control" id="similar_asset_items" name="similar_asset_items" readonly="readonly">
                    @if ($errors->has('similar_asset_items'))
                        <span class="help-block">
                            <strong>{{ $errors->first('similar_asset_items') }}</strong>
                        </span>
                    @endif
                </div>
            </div>


            <div class="form-group{{ $errors->has('unit') ? ' has-error' : '' }} required">
                <label for="unit" class="col-md-12 control-label">Enter FMV Per Unit</label>
                <div class="col-md-12">
                    <input type="text" placeholder="Units" class="form-control" id="unit" name="unit" value="{{ old('unit', $model->unit) }}">
                    @if ($errors->has('unit'))
                        <span class="help-block">
                            <strong>{{ $errors->first('unit') }}</strong>
                        </span>
                    @endif
                </div>
            </div>

            <div class="form-group{{ $errors->has('total_units') ? ' has-error' : '' }} required">
                <label for="total_units" class="col-md-12 control-label">Total FMV</label>
                <div class="col-md-12">
                    <input type="text" name="total_units" for="type" class="form-control" id="total_units" value="{{ old('total_units', $model->total_units) }}">
                    @if ($errors->has('total_units'))
                        <span class="help-block">
                            <strong>{{ $errors->first('total_units') }}</strong>
                        </span>
                    @endif
                </div>
            </div>
        </div>

        <div class="form-group{{ $errors->has('source') ? ' has-error' : '' }}">
            <label for="source" class="col-md-12 control-label">Enter SOURCE OF FMV</label>
            <div class="col-md-12">
                <input id="source" type="text" placeholder="Source" class="form-control" name="source" value="{{ old('source', $model->source) }}">
                @if ($errors->has('source'))
                    <span class="help-block">
                        <strong>{{ $errors->first('source') }}</strong>
                    </span>
                @endif
            </div>
        </div>

        <div class="form-group{{ $errors->has('attachment') ? ' has-error' : '' }}">
            <label for="workings_doc" class="col-md-12 control-label">Upload</label>
            <div class="col-md-12">
                <input type="name" id="upload2" name="name" class="form-control" disabled="disabled">
                <button type="button" class="browseBtn">Browse</button>
                <!-- <input type="file" id="file-name" name="file" class=""> -->
                <input id="workings_doc" type="file" placeholder="" class="form-control fileType" name="attachment">
                 <h6 class="disabled">{{ config('settings.file_size_limits.file_validation') }}</h6>
                @if ($errors->has('attachment'))
                    <span class="help-block">
                            <strong>{{ $errors->first('attachment') }}</strong>
                        </span>
                @endif

                @if($model->attachment !='')
                    <a href="{{asset('uploads/'.$model->attachment)}}" class="downloadIcon"
                       target="_blank"><i class="fa fa-download"></i></a>
                @endif

            </div>
        </div>
    </div>
<div class="form-group btnMainBx">

    <div class="col-md-4 col-sm-4 btn-backnextBx">
        <a href="{{ route('addlease.payments.index', ['id' => $lease->id]) }}" class="btn btn-danger"><i class="fa fa-arrow-left"></i> {{ env('BACK_LABEL')}}</a>
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

@section('footer-script')
    <script type="text/javascript">
        $(document).on('click', 'input[type="checkbox"]', function() {
            $('input[type="checkbox"]').not(this).prop('checked', false);

            if($(this).is(':checked') && $(this).val() == 'yes') {
                $('#hidden-fields').show();
            } else {
                $('#hidden-fields').hide();
            }
        });

        jQuery(document).ready(function($) {
            var $total = $('#total_units'),
                $value = $('#unit');
            $units = $("#similar_asset_items").val();
            $value.on('input', function(e) {
                var total = 0;
                $value.each(function(index, elem) {
                    if(!Number.isNaN(parseInt(this.value, 10)))
                        total = $units * parseInt(this.value, 10);
                });
                $total.val(total);
            });


            $('#workings_doc').change(function(){
            $('#workings_doc').show();
            var filename = $('#workings_doc').val();
            var or_name=filename.split("\\");
            $('#upload2').val(or_name[or_name.length-1]);
        });

        });
        $('.save_next').on('click', function (e) {
                e.preventDefault();
                $('input[name="action"]').val('next');
                $('#lease_fair').submit();
        });
    </script>
@endsection

