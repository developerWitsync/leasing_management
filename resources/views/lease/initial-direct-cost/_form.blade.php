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
                <input type="text" value="{{ old('total_initial_direct_cost', $model->total_initial_direct_cost)}}" class="form-control" id="total_initial_direct_cost" name="total_initial_direct_cost" readonly="readonly">
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
<script src="{{ asset('assets/plugins/bootbox/bootbox.min.js') }}"></script>
<script src="{{ asset('js/jquery-ui.js') }}"></script>
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
                @if(request()->segment('2') == 'initial-direct-cost' && request()->segment('3') == 'update')
                    url : '{{ route("addlease.initialdirectcost.updatesupplier", ['id' => $model->id]) }}',
                @else
                    url : '{{ route("addlease.initialdirectcost.addsupplier") }}',
                @endif
                type : 'get',
                success : function(response){
                    $('._form_supplier_details').html(response);

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
                            } else if($('select[name="'+i+'"]').length ){
                                $('select[name="'+i+'"]').after('<span class="help-block error_via_ajax" style="color:red">\n' +
                                    '                        <strong>'+e+'</strong>\n' +
                                    '                    </span>');
                            }
                        });
                    } else {

                        $('._form_supplier_details').html(response);
                        
                        $('._form_supplier_details .expense_date').datepicker({
                            dateFormat: 'dd-M-yy'
                        });

                        // alert("asfsdf");

                    }
                }
            });
        });

        $('#myModal').on('hidden.bs.modal', function () {
            // will only come inside after the modal is shown
            var total = 0;
            $('.supplier_details_amount').each(function(){
                total = parseFloat(total) + parseFloat($(this).text());
            });
            $('#total_initial_direct_cost').val(total);
        });

        //create supplier details on the update pop up...
        $(document.body).on('submit', '#supplier_details_form_update', function(e){
            e.preventDefault();
            $.ajax({
                url : "{{ route('addlease.initialdirectcost.createsupplier') }}",
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
                            } else if($('select[name="'+i+'"]').length ){
                                $('select[name="'+i+'"]').after('<span class="help-block error_via_ajax" style="color:red">\n' +
                                    '                        <strong>'+e+'</strong>\n' +
                                    '                    </span>');
                            }
                        });
                    } else {
                        window.location.reload();
                    }
                }
            });
        });

        //delete supplier details on the update pop up
        $(document.body).on('click', '.supplier_details_form_delete', function(e){
            var supplier_id = $(this).data('supplier_id');
            var lease_id = $(this).data('lease_id');
            var that = $(this);
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
                            url : "/lease/initial-direct-cost/delete-supplier/"+supplier_id+'/'+lease_id,
                            type : 'delete',
                            success : function(response){
                            if(response['status']){
                            $(that).parent('td').parent('tr').remove();
                                    }
                                }
                            });
                        }
                    }
                });
        });

        //delete supplier details on the create pop up
        //@todo Need to implement the bootbox
        $(document.body).on('click', '.supplier_create_details_form_delete', function(e){
            var supplier_id = $(this).data('supplier_id');
            var that = $(this);
         
                            $.ajax({
                            url : "/lease/initial-direct-cost/delete-create-supplier/"+supplier_id,
                            type : 'delete',
                            success : function(response){
                            if(response['status']){
                            $(that).parent('td').parent('tr').remove();
                                    }
                                }
                            });
                      
        });

        $(document.body).on('click', '.supplier_details_form_edit', function(e){
            var supplier_id = $(this).data('supplier_id');
            var lease_id = $(this).data('lease_id');
            var that = $(this);
            $.ajax({
                url : "/lease/initial-direct-cost/delete-supplier/"+supplier_id+'/'+lease_id,
                type : 'delete',
                success : function(response){
                    if(response['status']){
                        $(that).parent('td').parent('tr').remove();
                    }
                }
            });
        });
        $(function(){
            $('.expense_date').datepicker({
                dateFormat: 'dd-M-yy'
            });
        })


    </script>
@endsection