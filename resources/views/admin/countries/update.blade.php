@extends('layouts.admin.master')

@section('title')
    @lang('_page_titles.Admin') | Edit Country - {{ $country->name}}
@endsection

@section('header-styles')
@endsection
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-12">
                <div class="breadcrumb-holder">
                    <h1 class="main-title float-left">Update Country | {{ $country->name }}</h1>
                    <ol class="breadcrumb float-right">
                        <li class="breadcrumb-item">@lang('_sidebar.Dashboard')</li>
                        <li class="breadcrumb-item active">Update Country</li>
                    </ol>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
        <!-- end row -->

        <div class="row">

            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">

                <div class="card mb-3">

                    <div class="card-header">
                        <h3><i class="fa fa-file-o"></i> Update Country </h3>
                        Update the country details here.
                    </div>


                    <div class="card-body">

                        <form method="post" action="{{ route('admin.countries.update', ['id' => $country->id]) }}">
                            {{ @csrf_field() }}

                            <div class="form-row">

                                <div class="form-group col-md-6">
                                    <label for="name">Name (required)</label>
                                    <input type="text" class="form-control @if($errors->has('name')) is-invalid @endif" value="{{ old('name', $country->name) }}" name="name" id="name" placeholder="Country Name" autocomplete="off">
                                    @if($errors->has('name'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('name') }}
                                        </div>
                                    @endif
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="iso_code">ISO CODE (required)</label>
                                    <input type="text" class="form-control @if($errors->has('iso_code')) is-invalid @endif" id="iso_code" placeholder="ISO CODE" name="iso_code" value="{{ old('iso_code', $country->iso_code) }}" autocomplete="off">
                                    @if($errors->has('iso_code'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('iso_code') }}
                                        </div>
                                    @endif
                                </div>

                            </div>


                            <div class="form-row">

                                <div class="form-group col-md-6">
                                    <label for="latitude">Latitude</label>
                                    <input type="text" name="latitude" class="form-control @if($errors->has('latitude')) is-invalid @endif" id="latitude" value="{{ old('latitude', $country->latitude) }}" placeholder="Latitude" autocomplete="off">
                                    @if($errors->has('latitude'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('latitude') }}
                                        </div>
                                    @endif
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="longitude">Longitude</label>
                                    <input type="text" class="form-control @if($errors->has('longitude')) is-invalid @endif" id="longitude" name="longitude" placeholder="Longitude" value="{{ old('longitude', $country->longitude) }}" autocomplete="off">
                                    @if($errors->has('longitude'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('longitude') }}
                                        </div>
                                    @endif
                                </div>

                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="status">Status (required)</label>
                                    <select name="status" id="status" class="form-control @if($errors->has('status')) is-invalid @endif">
                                        <option value="">Select Status</option>
                                        <option value="1" @if(old('status', $country->status) == "1") selected="selected" @endif>Enable</option>
                                        <option value="0" @if(old('status', $country->status) == "0") selected="selected" @endif>Disable</option>
                                    </select>
                                    @if($errors->has('status'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('status') }}
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <button class="btn btn-primary" type="submit">Submit</button>
                            <a href="{{ route('admin.countries.index') }}" class="btn btn-danger">Cancel</a>

                        </form>

                    </div>
                </div><!-- end card-->
            </div>

        </div>
    </div>
@endsection
@section('footer-script')

@endsection