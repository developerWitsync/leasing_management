@extends('layouts.app')
@section('header-styles')
    <!-- BEGIN CSS for this page -->
    <link rel="stylesheet" href="{{ asset('css/jquery-ui.css') }}">
    <!-- END CSS for this page -->
@endsection
@section('content')
    <div class="panel panel-default">
        <div class="panel-heading">Lease Escalations</div>

        <div class="panel-body">
            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif

            <div class="tab-content" style="padding: 0px;">
                <div role="tabpanel" class="tab-pane active">
                    <form role="form" action="{{ route('lease.esacalation.applicablestatus', ['id' => $lease->id]) }}" class="form-horizontal" method="post" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <div class="form-group{{ $errors->has('escalation_clause_applicable') ? ' has-error' : '' }} required">
                            <label for="escalation_clause_applicable" class="col-md-4 control-label">Any Escalation Clause Applicable</label>
                            <div class="col-md-6 form-check form-check-inline">
                                <input class="form-check-input" name="escalation_clause_applicable" id="yes" type="checkbox" value="yes" @if(old('escalation_clause_applicable', $lease->escalation_clause_applicable) == "yes") checked="checked" @endif @if($subsequent_modify_required && $lease->escalation_clause_applicable == "yes") disabled="disabled" @endif>
                                <label class="form-check-label" for="yes" id="yes" style="vertical-align: 4px">Yes</label><br>
                                <input class="form-check-input" name="escalation_clause_applicable" id="no" type="checkbox" value="no" @if(old('escalation_clause_applicable', $lease->escalation_clause_applicable)  == "no") checked="checked" @endif @if($subsequent_modify_required) disabled="disabled" @endif>
                                <label class="form-check-label" for="no" id="no" style="vertical-align: 4px">No</label>
                                @if ($errors->has('escalation_clause_applicable'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('escalation_clause_applicable') }}</strong>
                                    </span>
                                @endif
                                @if($subsequent_modify_required && $lease->escalation_clause_applicable == "yes")
                                    <input type="hidden" name="escalation_clause_applicable" value="{{ $lease->escalation_clause_applicable }}">
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-success">
                                    Submit
                                </button>
                            </div>
                        </div>
                    </form>

                    @if($lease->escalation_clause_applicable == 'yes')
                        @include('lease.escalation._list_escalation')
                    @endif

                    <div class="form-group btnMainBx">
                        <div class="col-md-6 btn-backnextBx">

                            <a href="{{ route('addlease.durationclassified.index', ['id' => $lease->id]) }}" class="btn btn-danger">Back</a>
                            @if($lease->escalation_clause_applicable == 'no')
                                <a href="{{ route('addlease.lowvalue.index', ['id' => $lease->id]) }}" class="btn btn-primary">Next</a>
                            @elseif($show_next)
                                <a href="{{ route('addlease.lowvalue.index', ['id' => $lease->id]) }}" class="btn btn-primary">Next</a>
                            @endif

                        </div>
                        <div class="col-md-6 btnsubmitBx">
                           &nbsp;
                        </div>
                    </div>

                </div>



            </div>
        </div>
    </div>
@endsection
@section('footer-script')
    <script>
        $(document).ready(function(){
            $(document).on('click', 'input[type="checkbox"]', function() {
                $('input[type="checkbox"]').not(this).prop('checked', false);
            });
        });
    </script>
@endsection