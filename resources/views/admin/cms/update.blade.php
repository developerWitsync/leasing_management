@extends('layouts.admin.master')

@section('title')
    @lang('_page_titles.Admin') | Edit Cms - {{ $cms->title}}
@endsection

@section('header-styles')
@endsection
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-12">
                <div class="breadcrumb-holder">
                    <h1 class="main-title float-left">Update Cms | {{ $cms->title }}</h1>
                    <ol class="breadcrumb float-right">
                        <li class="breadcrumb-item">@lang('_sidebar.Dashboard')</li>
                        <li class="breadcrumb-item active">Update Cms</li>
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
                        <h3><i class="fa fa-file-o"></i> Update Cms </h3>
                        Update the cms details here.
                    </div>


                    <div class="card-body">

                        <form method="post" action="{{ route('admin.cms.update', ['id' => $cms->id]) }}">
                            {{ @csrf_field() }}

                             <div class="form-row">

                                <div class="form-group col-md-6">
                                    <label for="title">Page Title(required)</label>
                                    <input type="text" class="form-control @if($errors->has('title')) is-invalid @endif" value="{{ old('title',$cms->title) }}" name="title" id="title" placeholder="Page Title" autocomplete="off">
                                    @if($errors->has('title'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('title') }}
                                        </div>
                                    @endif
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="slug">Page Slug(required)</label>
                                    <input type="text" class="form-control @if($errors->has('slug')) is-invalid @endif" id="slug" placeholder="Page Slug" name="slug" value="{{ old('slug',$cms->slug) }}" autocomplete="off">
                                    @if($errors->has('slug'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('slug') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="form-row">
                                  <div class="form-group col-md-12">
                                <label for="description">Description(required)</label>
                                <textarea rows="3" class="form-control editor @if($errors->has('description')) is-invalid @endif" id="description" name="description">{{ old('description',$cms->description) }}</textarea>
                                @if($errors->has('description'))
                                    <div class="invalid-feedback" style="display: block">
                                        {{ $errors->first('description') }}
                                    </div>
                                @endif
                            </div>
                            </div>
                            <div class="form-row">
                                 <div class="form-group col-md-6">
                                    <label for="meta_title">Meta Tag Title(required)</label>
                                    <input type="text" name="meta_title" class="form-control @if($errors->has('meta_title')) is-invalid @endif" id="meta_title" value="{{ old('meta_title',$cms->meta_title) }}" placeholder="Meta Tag Ttile" autocomplete="off">
                                    @if($errors->has('meta_title'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('meta_title') }}
                                        </div>
                                    @endif
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="meta_description">Meta Tag Description(required) </label>
                                    <input type="text" class="form-control @if($errors->has('meta_description')) is-invalid @endif" id="meta_description" name="meta_description" placeholder="Meta Tag Description" value="{{ old('meta_description',$cms->meta_description) }}" autocomplete="off">
                                    @if($errors->has('meta_description'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('meta_description') }}
                                        </div>
                                    @endif
                                </div>
                                </div>

                            <div class="form-row">
                                 <div class="form-group col-md-6">
                                    <label for="meta_keyword">Meta Tag Keyword(required) </label>
                                    <input type="text" class="form-control @if($errors->has('meta_keyword')) is-invalid @endif" id="meta_keyword" name="meta_keyword" placeholder="Meta Tag Keyword" value="{{ old('meta_keyword',$cms->meta_keyword) }}" autocomplete="off">
                                    @if($errors->has('meta_keyword'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('meta_keyword') }}
                                        </div>
                                    @endif
                                </div>
                            
                                <div class="form-group col-md-6">
                                    <label for="status">Status (required)</label>
                                    <select name="status" id="status" class="form-control @if($errors->has('status')) is-invalid @endif">
                                        <option value="">Select Status</option>
                                        <option value="1" @if(old('status', $cms->status) == "1") selected="selected" @endif>Enable</option>
                                        <option value="0" @if(old('status', $cms->status) == "0") selected="selected" @endif>Disable</option>
                                    </select>
                                    @if($errors->has('status'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('status') }}
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <button class="btn btn-primary" type="submit">Submit</button>
                            <a href="{{ route('admin.cms.index') }}" class="btn btn-danger">Cancel</a>

                        </form>

                    </div>
                </div><!-- end card-->
            </div>

        </div>
    </div>
@endsection
@section('footer-script')

@endsection