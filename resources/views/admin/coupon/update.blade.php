@extends('layouts.admin.master')

@section('title')
    @lang('_page_titles.Admin') | Update Coupon
@endsection

@section('header-styles')
    <link href="{{ asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css"/>
@endsection
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-12">
                <div class="breadcrumb-holder">
                    <h1 class="main-title float-left">Update Coupon</h1>
                    <ol class="breadcrumb float-right">
                        <li class="breadcrumb-item">@lang('_sidebar.Dashboard')</li>
                        <li class="breadcrumb-item active">Create Coupon</li>
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
                        <h3><i class="fa fa-file-o"></i> Update Coupon </h3>
                        Already created coupon code can be updated here.
                    </div>


                    <div class="card-body">
                        @include('admin.coupon._form')
                    </div>
                </div><!-- end card-->
            </div>

        </div>
    </div>
@endsection
@section('footer-script')
    <script src="{{ asset('assets/plugins/select2/js/select2.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('.select2').select2({
                placeholder: 'Search for a user',
                ajax: {
                    url: '{{ route('admin.coupon.searchusers') }}',
                    dataType: 'json',
                    processResults: function (data, params) {
                        return {
                            results: data
                        };
                    },
                    cache: true
                },
                escapeMarkup: function (markup) {
                    return markup;
                }, // let our custom formatter work
                minimumInputLength: 1,
                templateResult: formatUser,
                templateSelection: formatUserSelection
            });

            function formatUser (user) {
                console.log(user);
                if (user.loading) {
                    return user.text;
                } else {
                    var markup = "<div class='select2-result-repository clearfix'>" +
                        "<div class='select2-result-repository__meta'>" +
                        "<div class='select2-result-repository__title'>" + user.authorised_person_name + "</div>";

                    // if (repo.description) {
                    //     markup += "<div class='select2-result-repository__description'>" + repo.description + "</div>";
                    // }

                    markup += "<div class='select2-result-repository__statistics'>" +
                        "<div class='select2-result-repository__forks'><i class='fa fa-user'></i> " + user.email + "</div>" +
                        "<div class='select2-result-repository__watchers'><i class='fa fa-group'></i> " + user.legal_entity_name  + " </div>" +
                        "</div>" +
                        "</div></div>";

                    return markup;
                }
            }

            function formatUserSelection (user) {
                return user.email;
            }
        });
    </script>
@endsection