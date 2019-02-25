@extends('layouts.admin.master')

@section('title')
    @lang('_page_titles.Admin') | Create Cms
@endsection

@section('header-styles')
@endsection
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-12">
                <div class="breadcrumb-holder">
                    <h1 class="main-title float-left">Create Cms</h1>
                    <ol class="breadcrumb float-right">
                        <li class="breadcrumb-item">@lang('_sidebar.Dashboard')</li>
                        <li class="breadcrumb-item active">Create Cms</li>
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
                        <h3><i class="fa fa-file-o"></i> Create Cms </h3>
                        New cms can be added from here.
                    </div>


                    <div class="card-body">

                        <form method="post" action="{{ route('admin.cms.create') }}">
                            {{ @csrf_field() }}

                            <div class="form-row">

                                <div class="form-group col-md-12">
                                    <label for="title">Page Title(required)</label>
                                    <input type="text" class="form-control @if($errors->has('title')) is-invalid @endif" value="{{ old('title') }}" name="title" id="title" placeholder="Page Title" autocomplete="off">
                                    @if($errors->has('title'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('title') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="form-row">
                                  <div class="form-group col-md-12">
                                <label for="description">Description(required)</label>
                                <textarea rows="3" class="form-control editor @if($errors->has('description')) is-invalid @endif" id="description" name="description">{{ old('description') }}</textarea>
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
                                    <input type="text" name="meta_title" class="form-control @if($errors->has('meta_title')) is-invalid @endif" id="meta_title" value="{{ old('meta_title') }}" placeholder="Meta Tag Ttile" autocomplete="off">
                                    @if($errors->has('meta_title'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('meta_title') }}
                                        </div>
                                    @endif
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="meta_description">Meta Tag Description(required) </label>
                                    <input type="text" class="form-control @if($errors->has('meta_description')) is-invalid @endif" id="meta_description" name="meta_description" placeholder="Meta Tag Description" value="{{ old('meta_description') }}" autocomplete="off">
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
                                    <input type="text" class="form-control @if($errors->has('meta_keyword')) is-invalid @endif" id="meta_keyword" name="meta_keyword" placeholder="Meta Tag Keyword" value="{{ old('meta_keyword') }}" autocomplete="off">
                                    @if($errors->has('meta_keyword'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('meta_keyword') }}
                                        </div>
                                    @endif
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="status">Status(required)</label>
                                    <select name="status" id="status" class="form-control @if($errors->has('status')) is-invalid @endif">
                                        <option value="">Select Status</option>
                                        <option value="0" @if(old('status') == "0") selected="selected" @endif>Enable</option>
                                        <option value="1" @if(old('status') == "1") selected="selected" @endif>Disable</option>
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

    <script src="https://cdn.ckeditor.com/4.10.1/standard/ckeditor.js"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            // unless user specified own height.
            // CKEDITOR.config.height = 150;
            // CKEDITOR.config.width = 'auto';
            window.CKEDITOR_BASEPATH = '{{ asset('assets/js/') }}';
            var editorElement = CKEDITOR.document.getById( 'description' );
            console.log(editorElement);
            CKEDITOR.replace( 'description');
        });
    </script>
@endsection