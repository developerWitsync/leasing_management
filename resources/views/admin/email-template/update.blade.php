@extends('layouts.admin.master')

@section('title')
    @lang('_page_titles.Admin') | Edit Email Template - {{ $template->title }}
@endsection

@section('header-styles')
    <!-- BEGIN CSS for this page -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/trumbowyg/ui/trumbowyg.min.css') }}">
    <!-- END CSS for this page -->
@endsection
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-12">
                <div class="breadcrumb-holder">
                    <h1 class="main-title float-left">@lang('_emailtemplates.update.title') | {{ $template->title }}</h1>
                    <ol class="breadcrumb float-right">
                        <li class="breadcrumb-item">@lang('_sidebar.Dashboard')</li>
                        <li class="breadcrumb-item active">@lang('_emailtemplates.update.header')</li>
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
                        <h3><i class="fa fa-file-o"></i> @lang('_emailtemplates.update.title') </h3>
                        @lang('_emailtemplates.update.instruction')
                    </div>


                    <div class="card-body">

                        <form method="post" action="{{ route('admin.emailtemplates.edit', ['id' => $template->id]) }}">
                            {{ @csrf_field() }}

                            <div class="form-row">

                                <div class="form-group col-md-6">
                                    <label for="title">Title (required)</label>
                                    <input type="text" class="form-control @if($errors->has('title')) is-invalid @endif" value="{{ old('title', $template->title) }}" name="title" id="title" placeholder="Title" autocomplete="off">
                                    @if($errors->has('title'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('title') }}
                                        </div>
                                    @endif
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="template_subject">Subject (required)</label>
                                    <input type="text" class="form-control @if($errors->has('template_subject')) is-invalid @endif" id="template_subject" placeholder="Subject" name="template_subject" value="{{ old('template_subject', $template->template_subject) }}" autocomplete="off">
                                    @if($errors->has('template_subject'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('template_subject') }}
                                        </div>
                                    @endif
                                </div>

                            </div>


                            <div class="form-row">

                                <div class="form-group col-md-6">
                                    <label for="template_code">Template Code</label>
                                    <input type="text" name="template_code" class="form-control @if($errors->has('template_code')) is-invalid @endif" id="template_code" value="{{ old('template_code', $template->template_code) }}" placeholder="Template Code" autocomplete="off" disabled="disabled">
                                    @if($errors->has('template_code'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('template_code') }}
                                        </div>
                                    @endif
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="template_special_variables">Special Variables</label>
                                    <input type="text" class="form-control @if($errors->has('template_special_variables')) is-invalid @endif" id="template_special_variables" name="template_special_variables" placeholder="Special Variables" value="{{ old('template_special_variables', $template->template_special_variables) }}" autocomplete="off" disabled="disabled">
                                    @if($errors->has('template_special_variables'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('template_special_variables') }}
                                        </div>
                                    @endif
                                </div>

                            </div>

                            <div class="form-group">
                                <label for="template_body">Template Body(required)</label>
                                <textarea rows="3" class="form-control editor @if($errors->has('template_body')) is-invalid @endif" id="template_body" name="template_body">{{ old('template_body',$template->template_body) }}</textarea>
                                @if($errors->has('template_body'))
                                    <div class="invalid-feedback" style="display: block">
                                        {{ $errors->first('template_body') }}
                                    </div>
                                @endif
                            </div>

                            <button class="btn btn-primary" type="submit">Submit</button>
                            <a href="{{ route('admin.emailtemplates.index') }}" class="btn btn-danger">Cancel</a>

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
            var editorElement = CKEDITOR.document.getById( 'template_body' );
            console.log(editorElement);
            CKEDITOR.replace( 'template_body');
        });
    </script>
@endsection