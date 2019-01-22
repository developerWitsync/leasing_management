<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">&times;</button>
    <h4 class="modal-title">Lease Lock Year</h4>
</div>
<div class="modal-body">

    <div class="status_sucess" style="display:none;">
        <div class="alert alert-success">

        </div>
    </div>


    <form id="edit_settings1" class="form-horizontal" method="POST" action="{{ route('settings.leaselockyear.editleaselockyear', ['id' => $lease_lock_year->id]) }}">
        <div class="form-group{{ $errors->has('start_date') ? ' has-error' : '' }}">
            <label for="start_date_edit" class="col-md-4 control-label">Audit Year Start On</label>

            <div class="col-md-6">
                <input id="start_date_edit" type="text" placeholder="Audit Year Start On" class="form-control" name="start_date" value="{{  old('start_date',date('m/d/Y', strtotime($lease_lock_year->start_date))) }}">

                <div id="error_section">
                    @if ($errors->has('start_date'))
                        <span class="help-block">
                        <strong>{{ $errors->first('start_date') }}</strong>
                    </span>
                    @endif
                </div>
            </div>
        </div>
        <div class="form-group{{ $errors->has('end_date') ? ' has-error' : '' }}">
            <label for="end_date_edit" class="col-md-4 control-label">Audit Year End On</label>

            <div class="col-md-6">
                <input id="end_date_edit" type="text" placeholder="Audit Year end On" class="form-control" name="end_date" value="{{old('end_date', date('m/d/Y', strtotime($lease_lock_year->end_date)))}}">

                <div id="error_section1">
                    @if ($errors->has('end_date'))
                        <span class="help-block">
                            <strong>{{ $errors->first('end_date') }}</strong>
                        </span>
                    @endif
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="col-md-8 col-md-offset-4">
                <button type="submit" class="btn btn-primary">
                    Save
                </button>
            </div>
        </div>

    </form>
</div>
<div class="modal-footer">
    &nbsp;&nbsp;&nbsp;
</div>
