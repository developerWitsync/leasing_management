<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">&times;</button>
    <h4 class="modal-title">Update Escalation Consistency Gap</h4>
</div>
<div class="modal-body">

    <div class="status_sucess" style="display:none;">
        <div class="alert alert-success">

        </div>
    </div>

    <form id="edit_settings" class="form-horizontal" method="POST" action="{{ route('settings.leaseclassification.editescalationconsistencygap', ['id' => $escalation_consistency_gap->id]) }}">
        <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
            <label for="title" class="col-md-4 control-label">Number</label>

            <div class="col-md-6">
                <input id="title" type="text" placeholder="Number" class="form-control" name="title" value="{{ old('title', $escalation_consistency_gap->years) }}" autofocus>

                <div id="error_section">
                    @if ($errors->has('title'))
                        <span class="help-block">
                        <strong>{{ $errors->first('title') }}</strong>
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
