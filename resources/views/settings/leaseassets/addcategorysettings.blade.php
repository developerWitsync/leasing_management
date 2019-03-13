@extends('layouts.app')
@section('header-styles')
    <!-- BEGIN CSS for this page -->
    <link rel="stylesheet" href="{{ asset('css/jquery-ui.css') }}">
    <!-- END CSS for this page -->
@endsection
@section('content')
    <div class="panel panel-default">
        <div class="panel-heading">Settings | Currencies Settings</div>

        <div class="panel-body">
            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif

            @include('settings._menubar')

            <div class="tab-content">
                <div role="tabpanel" class="tab-pane active">
                    <div class="panel panel-info">
                        <div class="panel-heading">Add {{ $lease_asset_category->title }} Type</div>
                        <div class="panel-body">
                            <form method="post" class="form-horizontal">
                                {{ csrf_field() }}

                                <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }} required">
                                    <label for="title" class="col-md-4 control-label">Enter {{ $lease_asset_category->title }} Type</label>
                                    <div class="col-md-8">
                                        <input type="text" placeholder="Title" name="title" class="form-control">
                                        @if ($errors->has('title'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('title') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                @if($is_excluded == 0)
                                    <div class="form-group{{ $errors->has('depreciation_method_id') ? ' has-error' : '' }} required">
                                        <label for="depreciation_method_id" class="col-md-4 control-label">Depreciation Method</label>
                                        <div class="col-md-8">

                                            <select id="depreciation_method_id" class="form-control disabled" name="depreciation_method_id" readonly="readonly">
                                                <option value="">--Select Depreciation Method--</option>
                                                @foreach($depreciation_method as $method)
                                                    <option value="{{ $method->id }}" @if($method->is_default == '1') selected="selected" @endif>{{ $method->title }}</option>
                                                @endforeach
                                            </select>

                                            @if ($errors->has('depreciation_method_id'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('depreciation_method_id') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group{{ $errors->has('depreciation_method_id') ? ' has-error' : '' }} required">
                                        <label for="initial_valuation_model_id" class="col-md-4 control-label">Initial Valuation Model</label>
                                        <div class="col-md-8">

                                            <select id="initial_valuation_model_id" class="form-control disabled" name="initial_valuation_model_id" readonly="readonly">
                                                <option value="">--Select Valuation Model--</option>
                                                @foreach($initial_valuation_model as $model)
                                                    <option value="{{ $model->id }}" @if($model->is_default == '1') selected="selected" @endif>{{ $model->title }}</option>
                                                @endforeach
                                            </select>

                                            @if ($errors->has('initial_valuation_model_id'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('initial_valuation_model_id') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <input type="hidden" value="1" name="is_excluded">
                                @else
                                    <input type="hidden" value="0" name="is_excluded">
                                @endif

                                <div class="form-group">
                                    <div class="col-md-6 col-md-offset-4">
                                        <button type="submit" class="btn btn-success">
                                            Submit
                                        </button>
                                    </div>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('footer-script')

@endsection