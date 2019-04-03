<a href="javascript:void(0);" data-toggle="modal" data-target="#contactUsModal"
   class="badge btn-info contactUsCommon">
    <i class="fa fa-envelope-o"></i>
    Contact Support
</a>

<div id="contactUsModal" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Contact Support</h4>
            </div>
            <div class="modal-body">

                <div class="alert alert-success raise_ticket_success_message" style="display: none;">

                </div>

                <form class="form-horizontal" id="raise_ticket_form" action="{{ route('support.raise.ticket') }}">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label for="subject" class="col-md-12 control-label">Subject</label>
                        <div class="col-md-12">
                            <input id="subject" type="text" placeholder="Subject"
                                   class="form-control" name="subject" value="" autofocus>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="message" class="col-md-12 control-label">How can we help you today?</label>
                        <div class="col-md-12">
                            <textarea class="form-control" name="message" id="message"></textarea>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="attachment" class="col-md-12 control-label">Attachments</label>
                        <div class="col-md-12">
                            <input type="name" id="upload2" name="name" class="form-control" disabled="disabled">
                            <button type="button" class="browseBtn">Browse</button>
                            <input id="workings_doc" type="file" placeholder="" class="form-control fileType"
                                   name="file">
                            <h6 class="disabled">{{ config('settings.file_size_limits.file_validation') }}</h6>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="message" class="col-md-12 control-label">How critical is your request?</label>
                        <div class="col-md-12">
                            <select class="form-control" name="severity" id="severity">
                                <option value="">--Select Severity--</option>
                                <option value="1">Very Critical (High)</option>
                                <option value="2">Critical (Medium)</option>
                                <option value="3">Not Critical (Low)</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group" style="padding-top: 15px;">
                        <div class="col-md-4 col-sm-4">
                            <button type="submit" class="btn btn-success">
                                Submit </button>
                        </div>
                    </div>

                </form>
            </div>
            <div class="modal-footer">
                &nbsp;
            </div>
        </div>

    </div>
</div>