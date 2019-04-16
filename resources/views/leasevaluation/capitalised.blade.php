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
	@if(request()->segment(2) == 'valuation-capitalised')
		<script>var _is_capitalized = true;</script>
	@else
		<script>var _is_capitalized = false;</script>
	@endif
	@foreach($categories as $category)
		<script>
			fetchCategoryAssets({{$category->id}}, _is_capitalized);
		</script>
	@endforeach

	@if (session('status'))
		<script>
			$(function () {
				bootbox.dialog({
					message: '<div class="thank-you-pop">\n' +
							'\t\t\t\t\t\t\t<img src="{{ asset('images/round_tick.png') }}" alt="">\n' +
							'\t\t\t\t\t\t\t<h1>Thank You!</h1>\n' +
							'\t\t\t\t\t\t\t<p>Your Lease has been submitted successfully.</p>\n' +
							'\t\t\t\t\t\t\t<h3 class="cupon-pop">Lease ULA CODE : <span>{{ session('status') }}</span></h3>\n' +
							'\t\t\t\t\t\t\t\n' +
							' \t\t\t\t\t\t</div>',
					closeButton: true
				});
			});
		</script>
	@endif

@endsection