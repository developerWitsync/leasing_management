<form role="form" class="form-horizontal" method="post" enctype="multipart/form-data">
    {{ csrf_field() }}

    <div class="form-group{{ $errors->has('initial_direct_cost_involved') ? ' has-error' : '' }} required">
        <label for="name" class="col-md-4 control-label">Any Initial Direct Cost Involved</label>
        <div class="col-md-6 form-check form-check-inline" required>
            <input class="form-check-input" name="initial_direct_cost_involved" id="yes" type="checkbox" value="yes" @if(old('initial_direct_cost_involved', $model->initial_direct_cost_involved) == "yes") checked="checked" @endif>
            <label class="form-check-label" for="yes" id="yes" style="vertical-align: 4px">Yes</label><br>
            <input class="form-check-input" name="initial_direct_cost_involved" id="no" type="checkbox" value="no" @if(old('initial_direct_cost_involved', $model->initial_direct_cost_involved)  == "no") checked="checked" @endif>
            <label class="form-check-label" for="no" id="no" style="vertical-align: 4px">No</label>
            @if ($errors->has('initial_direct_cost_involved'))
                <span class="help-block">
                        <strong>{{ $errors->first('initial_direct_cost_involved') }}</strong>
                    </span>
            @endif
        </div>
    </div>
         <div class="hidden-group" id="hidden-fields" @if(old('initial_direct_cost_involved',$model->initial_direct_cost_involved ) == "yes") style="display:block;" @else  style="display:none;" @endif>
        <div class="form-group{{ $errors->has('currency') ? ' has-error' : '' }} required">
            <label for="currency" class="col-md-4 control-label">Currency</label>
            <div class="col-md-6 form-check form-check-inline">
                <input type="text" value="{{ $lease->lease_contract_id }}" class="form-control" id="currency" name="currency" readonly="readonly">
                @if ($errors->has('currency'))
                    <span class="help-block">
                        <strong>{{ $errors->first('currency') }}</strong>
                    </span>
                @endif
            </div>
        </div>


        <div class="form-group{{ $errors->has('total_initial_direct_cost') ? ' has-error' : '' }} required">
            <label for="total_initial_direct_cost" class="col-md-4 control-label">Total Initial Direct Cost</label>
            <div class="col-md-6 form-check form-check-inline">
                <input type="text" value="{{ $asset->total_initial_direct_cost }}" class="form-control" id="total_initial_direct_cost" name="total_initial_direct_cost" readonly="readonly">
                @if ($errors->has('total_initial_direct_cost'))
                    <span class="help-block">
                        <strong>{{ $errors->first('total_initial_direct_cost') }}</strong>
                    </span>
                @endif
            </div>
        </div>

    <div class="form-group{{ $errors->has('details') ? ' has-error' : '' }}">
        <label for="details" class="col-md-4 control-label"></label>
        <div class="col-md-6">
            <a data-toggle="modal" href="javascript:void(0);" class="btn btn-primary enter_supplier_details">Enter Details</a>
        </div>
    </div>
     </div>

    <div class="form-group">
        <div class="col-md-6 col-md-offset-4">

            <a href="{{ route('addlease.initialdirectcost.index', ['id' => $lease->id]) }}" class="btn btn-danger">Cancel</a>
            <button type="submit" class="btn btn-success">
                Submit
            </button>
        </div>
    </div>
</form>

<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content _form_supplier_details">
        </div>
    </div>
</div>

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

        $('.enter_supplier_details').on('click', function () {
            $.ajax({
                url : '{{ route("addlease.initialdirectcost.addsupplier") }}',
                type : 'get',
                success : function(response){
                    $('._form_supplier_details').html(response);
                    // $('.table_supplier_details').dataTable();
                    $("#myModal").modal('show');
                }
            });
        });

        $(document.body).on('submit', '#supplier_details_form', function (e) {
            e.preventDefault();
            $.ajax({
                url : '{{ route("addlease.initialdirectcost.addsupplier") }}',
                type : 'post',
                data : $(this).serialize(),
                success : function(response){
                    if(typeof(response['status'])!='undefined' && !response['status']) {
                        var errors = response['errors'];
                        $('.error_via_ajax').remove();
                        $.each(errors, function(i, e){
                            if($('input[name="'+i+'"]').length ){
                                $('input[name="'+i+'"]').after('<span class="help-block error_via_ajax" style="color:red">\n' +
                                    '                        <strong>'+e+'</strong>\n' +
                                    '                    </span>');
                            }
                        });
                    } else {
                        $('._form_supplier_details').html(response);
                    }
                }
            });
        });
    </script>
@endsection