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
        <div class="form-group{{ $errors->has('audit_year1_ended_on1') ? ' has-error' : '' }}">
            <label for="audit_year1_ended_on1" class="col-md-4 control-label">Audit Year 1 Ended On</label>

            <div class="col-md-6">
                <input id="audit_year1_ended_on1" type="text" placeholder="Audit Year 1 Ended On" class="form-control" name="audit_year1_ended_on1" value="{{  old('audit_year1_ended_on1',date('m/d/Y', strtotime($lease_lock_year->audit_year1_ended_on))) }}">

                <div id="error_section">
                    @if ($errors->has('audit_year1_ended_on1'))
                        <span class="help-block">
                        <strong>{{ $errors->first('audit_year1_ended_on1') }}</strong>
                    </span>
                    @endif
                </div>
            </div>
        </div>
        <div class="form-group{{ $errors->has('audit_year2_ended_on1') ? ' has-error' : '' }}">
            <label for="audit_year2_ended_on1" class="col-md-4 control-label">Audit Year 2 Ended On</label>

            <div class="col-md-6">
                <input id="audit_year2_ended_on1" type="text" placeholder="Audit Year 2 Ended On" class="form-control" name="audit_year2_ended_on1" value="{{old('audit_year2_ended_on1', date('m/d/Y', strtotime($lease_lock_year->audit_year2_ended_on)))}}">

                <div id="error_section1">
                    @if ($errors->has('audit_year2_ended_on1'))
                        <span class="help-block">
                        <strong>{{ $errors->first('audit_year2_ended_on1') }}</strong>
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
