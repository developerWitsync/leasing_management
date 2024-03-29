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

            @include('lease._subsequent_details')

            <div class="tab-content" style="padding: 0px;">
                <div role="tabpanel" class="tab-pane active">
                    <form role="form" action="{{ route('lease.esacalation.applicablestatus', ['id' => $lease->id]) }}"
                          class="form-horizontal" method="post" enctype="multipart/form-data" id="lease_esclation">
                        {{ csrf_field() }}
                        <div class="categoriesOuter clearfix">

                            <div class="form-group required">
                                <label for="asset_name" class="col-md-12 control-label">Lease Asset Name</label>
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

                            <div class="form-group{{ $errors->has('escalation_clause_applicable') ? ' has-error' : '' }} required">
                                <label for="escalation_clause_applicable" class="col-md-12 control-label">Any Escalation
                                    Clause Applicable</label>
                                <div class="col-md-12 form-check form-check-inline">
                                    <input class="form-check-input" name="escalation_clause_applicable" id="yes"
                                           type="checkbox" value="yes"
                                           @if(old('escalation_clause_applicable', $lease->escalation_clause_applicable) == "yes") checked="checked" @endif
                                           {{--@if($subsequent_modify_required && $lease->escalation_clause_applicable == "yes") disabled="disabled" @endif--}}>
                                    <label class="form-check-label" for="yes" id="yes"
                                           style="vertical-align: 4px">Yes</label><br>
                                    <input class="form-check-input" name="escalation_clause_applicable" id="no"
                                           type="checkbox" value="no"
                                           @if(old('escalation_clause_applicable', $lease->escalation_clause_applicable)  == "no") checked="checked" @endif
                                           {{--@if($subsequent_modify_required) disabled="disabled" @endif--}}>
                                    <label class="form-check-label" for="no" id="no"
                                           style="vertical-align: 4px">No</label>
                                    @if ($errors->has('escalation_clause_applicable'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('escalation_clause_applicable') }}</strong>
                                    </span>
                                    @endif
                                    {{--@if($subsequent_modify_required && $lease->escalation_clause_applicable == "yes")--}}
                                        {{--<input type="hidden" name="escalation_clause_applicable"--}}
                                               {{--value="{{ $lease->escalation_clause_applicable }}">--}}
                                    {{--@endif--}}
                                </div>
                            </div>

                            <div class="innerSubmit">
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-success">
                                        Submit
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>

                    @if($lease->escalation_clause_applicable == 'yes')
                        @include('lease.escalation._list_escalation')
                    @endif

                    <div class="form-group btnMainBx clearfix">
                        <div class="col-md-4 col-sm-4 btn-backnextBx">
                            <a href="{{ $back_url }}" class="btn btn-danger"><i
                                        class="fa fa-arrow-left"></i> {{ env('BACK_LABEL')}}</a>
                        </div>

                        <div class="col-md-8 col-sm-8 btn-backnextBx rightlign ">
                            @if($lease->escalation_clause_applicable == 'no')
                                <a href="{{ route('addlease.lowvalue.index', ['id' => $lease->id]) }}"
                                   class="btn btn-primary">{{ env('NEXT_LABEL') }} <i class="fa fa-arrow-right"></i></a>
                            @elseif($show_next)
                                <a href="{{ route('addlease.lowvalue.index', ['id' => $lease->id]) }}"
                                   class="btn btn-primary">{{ env('NEXT_LABEL') }} <i class="fa fa-arrow-right"></i></a>
                            @endif

                        </div>
                    </div>


                </div>
            </div>
        </div>
    </div>
@endsection
@section('footer-script')
    <script>
        $(document).ready(function () {
            $(document).on('click', 'input[type="checkbox"]', function () {
                $('input[type="checkbox"]').not(this).prop('checked', false);
            });
        });

    </script>
@endsection