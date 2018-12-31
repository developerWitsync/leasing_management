@extends('layouts.app')
@section('header-styles')
    <!-- BEGIN CSS for this page -->
    <link rel="stylesheet" href="{{ asset('css/jquery-ui.css') }}">
    <!-- END CSS for this page -->
@endsection
@section('content')
    <div class="panel panel-default">
        <div class="panel-heading">Add New Lease | Underlying Lease Assets</div>

        <div class="panel-body">
            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach (array_unique($errors->all()) as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{--@include('lease._menubar')--}}

            <div class="tab-content" style="padding: 0px;">
                <div role="tabpanel" class="tab-pane active">
                    @include('lease.lease-assets._create_assets')
                </div>
            </div>


        </div>
    </div>
@endsection
@section('footer-script')
    <script>
        $(document).ready(function () {
            $('#no_of_lease_assets').on('change', function () {
                //make a post request to a lease controller function to update the lease assets number
                $('.update_total_lease_assets').submit();
            });

            $('.asset_category').on('change', function () {
                var number = $(this).data('number');
                $.ajax({
                    url : '/lease/underlying-lease-assets/fetch-sub-categories/'+$(this).val(),
                    type : 'get',
                    dataType : 'json',
                    success : function(response){
                        $(".asset_sub_category_"+number).html(response['html']);
                    }
                })
            })
        });
    </script>
@endsection