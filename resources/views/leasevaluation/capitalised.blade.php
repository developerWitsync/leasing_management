@extends('layouts.app')
@section('header-styles')
    <link rel="stylesheet" href="{{ asset('css/jquery-ui.css') }}">
    <!-- BEGIN CSS for this page -->
    <link rel="stylesheet" type="text/css"
          href="{{ asset('assets/plugins/datatables/dataTables.bootstrap4.min.css') }}"/>
    <!-- END CSS for this page -->
@endsection
@section('content')
   <div class="leasingModuleOuter">
   		<!--<div class="leasingBreatcrum"><a href="">Dashboard</a>&nbsp; / &nbsp;<span>Lessee Leasing Module</span></div>-->
   		<div class="leasingMainHd">Lessee Leasing Module</div>
	   @foreach($categories as $category)
   			<span id="append_here_{{$category->id}}"></span>
	   @endforeach
   </div>
@endsection
@section('footer-script')
	<script src="{{ asset('js/pages/valuation_main.js') }}"></script>
	<script>var _is_capitalized = false;</script>
	@foreach($categories as $category)
		<script>
			fetchCategoryAssets({{$category->id}});
		</script>
	@endforeach
@endsection