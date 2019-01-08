
<form role="form" class="form-horizontal" method="post" enctype="multipart/form-data">
    {{ csrf_field() }}

    <div class="form-group{{ $errors->has('is_any_lease_incentives_receivable') ? ' has-error' : '' }} required">
        <label for="name" class="col-md-4 control-label">Any Lease Incentives Receivable</label>
        <div class="col-md-6 form-check form-check-inline" required>
            <input class="form-check-input" name="is_any_lease_incentives_receivable" id="yes" type="checkbox" value="yes" @if(old('is_any_lease_incentives_receivable', $model->is_any_lease_incentives_receivable) == "yes") checked="checked" @endif>
            <label class="form-check-label" for="yes" id="yes" style="vertical-align: 4px">Yes</label><br>
            <input class="form-check-input" name="is_any_lease_incentives_receivable" id="no" type="checkbox" value="no" @if(old('is_any_lease_incentives_receivable', $model->is_any_lease_incentives_receivable)  == "no") checked="checked" @endif>
            <label class="form-check-label" for="no" id="no" style="vertical-align: 4px">No</label>
            @if ($errors->has('is_any_lease_incentives_receivable'))
                <span class="help-block">
                        <strong>{{ $errors->first('is_any_lease_incentives_receivable') }}</strong>
                    </span>
            @endif
        </div>
    </div>
         <div class="hidden-group" id="hidden-fields" @if(old('is_any_lease_incentives_receivable',$model->is_any_lease_incentives_receivable ) == "yes") style="display:block;" @else  style="display:none;" @endif>
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

        <div class="form-group{{ $errors->has('total_lease_incentives') ? ' has-error' : '' }} required">
            <label for="total_lease_incentives" class="col-md-4 control-label">Total Initial Direct Cost</label>
            <div class="col-md-6 form-check form-check-inline">
                <input type="text" value="{{ old('total_lease_incentives', $model->total_lease_incentives)}}" class="form-control" id="total_lease_incentives" name="total_lease_incentives" readonly="readonly">
                @if ($errors->has('total_lease_incentives'))
                    <span class="help-block">
                        <strong>{{ $errors->first('total_lease_incentives') }}</strong>
                    </span>
                @endif
            </div>
        </div>

    <div class="form-group{{ $errors->has('details') ? ' has-error' : '' }}">
        <label for="details" class="col-md-4 control-label"></label>
        <div class="col-md-6">
            <a data-toggle="modal" href="javascript:void(0);" class="btn btn-primary enter_customer_details">Enter Details</a>
        </div>
    </div>
     </div>

    <div class="form-group">
        <div class="col-md-6 col-md-offset-4">

            <a href="{{ route('addlease.leaseincentives.index', ['id' => $lease->id]) }}" class="btn btn-danger">Cancel</a>
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
        <div class="modal-content _form_customer_details">
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

        $('.enter_customer_details').on('click', function () {
           $.ajax({
                @if(request()->segment('2') == 'lease-incentives' && request()->segment('3') == 'update')
                    url : '{{ route("addlease.leaseincentives.updatecustomer", ['id' => $model->id]) }}',
                @else
                    url : '{{ route("addlease.leaseincentives.addcustomer") }}',
                @endif
                type : 'get',
                success : function(response){
                    $('._form_customer_details').html(response);

                    $("#myModal").modal('show');
                }
            });
        });

        $(document.body).on('submit', '#customer_details_form', function (e) {
           // $('.incentive_date').datepicker({dateFormat: "dd-M-yy"});
            e.preventDefault();
            $.ajax({
                url : '{{ route("addlease.leaseincentives.addcustomer") }}',
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
                             else if($('select[name="'+i+'"]').length){
                                 $('select[name="'+i+'"]').after('<span class="help-block error_via_ajax" style="color:red">\n' +
                                    '                        <strong>'+e+'</strong>\n' +
                                    '                    </span>');
                            }
                        });
                    } else {
                        $('._form_customer_details').html(response);
                        //$( ".incentive_date" ).datepicker();
                        /*$('._form_customer_details  .incentive_date').datepicker({
                             dateFormat: "dd-M-yy"
                         });
*/                    }
                }
            });
        });

        $('#myModal').on('hidden.bs.modal', function () {
            // will only come inside after the modal is shown
            var total = 0;
            $('.customer_details_amount').each(function(){
                total = parseFloat(total) + parseFloat($(this).text());
            });
            $('#total_lease_incentives').val(total);
        });

        //create supplier details on the update pop up...
        $(document.body).on('submit', '#customer_details_form_update', function(e){
            e.preventDefault();
            $.ajax({
                url : "{{ route('addlease.leaseincentives.createcustomer') }}",
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
                            else if($('select[name="'+i+'"]').length){
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

        //delete customer details on the update pop up
        
        $(document.body).on('click', '.customer_details_form_delete', function(e){
            var customer_id = $(this).data('customer_id');
            var lease_id = $(this).data('lease_id');
            var rowCount = $("tr.customer").length;
            var that = $(this);
             if(rowCount == 1){
                var modal = bootbox.dialog({
                message: 'You can not delete this detail to do this you have to Select No Any Lease Incentive Receivable field',
                buttons: [
                {
                    label: "OK",
                    className: "btn btn-success pull-left",
                    callback: function() {
                    }
                }
                ],
                    show: false,
                    onEscape: function() {
                    modal.modal("hide");
                    }
                });
                    modal.modal("show");
            }
            else{
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
                            url : "/lease/lease-incentives/delete-customer/"+customer_id+'/'+lease_id,
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
            }
        }); 
        

        $(document.body).on('click', '.customer_details_form_edit', function(e){
            var customer_id = $(this).data('customer_id');
            var lease_id = $(this).data('lease_id');
            var that = $(this);
            $.ajax({
                url : "/lease/lease-incentives/delete-customer/"+customer_id+'/'+lease_id,
                type : 'delete',
                success : function(response){
                    if(response['status']){
                        $(that).parent('td').parent('tr').remove();
                    }
                }
            });
        });

        //delete customer details on the create pop up
         $(document.body).on('click', '.customer_create_details_form_delete', function(e){
            var customer_id = $(this).data('customer_id');
            var that = $(this);
            $.ajax({
                url : "/lease/lease-incentives/delete-create-customer/"+customer_id,
                type : 'delete',
                success : function(response){
                    if(response['status']){
                        $(that).parent('td').parent('tr').remove();
                    }
                }
            });
        });

    </script>
@endsection