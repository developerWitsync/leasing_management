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
   		<div class="landBg clearfix">
   			<div class="leaseTotalHd">
   				<h2>Land</h2>
   				<span>Total Lease <br/> Assets</span>
   				<strong>03</strong>
   				<div class="pagerButton">
   					<a href=""><img src="../assets/images/land-arrow-left1.png" class="img"><img src="../assets/images/land-arrow-left.png" class="over"></a>
   					<a href=""><img src="../assets/images/land-arrow-right1.png" class="img"><img src="../assets/images/land-arrow-right.png" class="over"></a>
   				</div>
   			</div>
   			<div class="leaseSlide">
   				<ul class="clearfix">
   					<li>
   						<div class="landType">2BHK Residential...</div>
   						<div class="leaseterms">
   							<span>
   								Lease Term
								<strong>11 Years 12 Months 8 weeks 10 days</strong>
   							</span>
   							<span>
   								Lease Expiring On
								<strong>2042-03-12</strong>
   							</span>
   							<span>
   								Undiscounted Lease Liability
								<strong>3066000</strong>
   							</span>
   						</div>
   					</li>
   					<li>
   						<div class="landType">2BHK Residential...</div>
   						<div class="leaseterms">
   							<span>
   								Lease Term
								<strong>11 Years 12 Months 8 weeks 10 days</strong>
   							</span>
   							<span>
   								Lease Expiring On
								<strong>2042-03-12</strong>
   							</span>
   							<span>
   								Undiscounted Lease Liability
								<strong>3066000</strong>
   							</span>
   						</div>
   					</li>
   					<li>
   						<div class="landType">2BHK Residential...</div>
   						<div class="leaseterms">
   							<span>
   								Lease Term
								<strong>11 Years 12 Months 8 weeks 10 days</strong>
   							</span>
   							<span>
   								Lease Expiring On
								<strong>2042-03-12</strong>
   							</span>
   							<span>
   								Undiscounted Lease Liability
								<strong>3066000</strong>
   							</span>
   						</div>
   					</li>
   					<li>
   						<div class="landType">2BHK Residential...</div>
   						<div class="leaseterms">
   							<span>
   								Lease Term
								<strong>11 Years 12 Months 8 weeks 10 days</strong>
   							</span>
   							<span>
   								Lease Expiring On
								<strong>2042-03-12</strong>
   							</span>
   							<span>
   								Undiscounted Lease Liability
								<strong>3066000</strong>
   							</span>
   						</div>
   					</li>
   				</ul>
   			</div>
   		</div>
   		<div class="landBg clearfix">
   			<div class="leaseTotalHd">
   				<h2>Other than Land</h2>
   				<span>Total Lease <br/> Assets</span>
   				<strong>12</strong>
   				<div class="pagerButton">
   					<a href=""><img src="../assets/images/land-arrow-left1.png" class="img"><img src="../assets/images/land-arrow-left.png" class="over"></a>
   					<a href=""><img src="../assets/images/land-arrow-right1.png" class="img"><img src="../assets/images/land-arrow-right.png" class="over"></a>
   				</div>
   			</div>
   			<div class="leaseSlide">
   				<ul class="clearfix">
   					<li>
   						<div class="landType2">2BHK Residential...</div>
   						<div class="leaseterms">
   							<span>
   								Lease Term
								<strong>11 Years 12 Months 8 weeks 10 days</strong>
   							</span>
   							<span>
   								Lease Expiring On
								<strong>2042-03-12</strong>
   							</span>
   							<span>
   								Undiscounted Lease Liability
								<strong>3066000</strong>
   							</span>
   						</div>
   					</li>
   					<li>
   						<div class="landType2">2BHK Residential...</div>
   						<div class="leaseterms">
   							<span>
   								Lease Term
								<strong>11 Years 12 Months 8 weeks 10 days</strong>
   							</span>
   							<span>
   								Lease Expiring On
								<strong>2042-03-12</strong>
   							</span>
   							<span>
   								Undiscounted Lease Liability
								<strong>3066000</strong>
   							</span>
   						</div>
   					</li>
   					<li>
   						<div class="landType2">2BHK Residential...</div>
   						<div class="leaseterms">
   							<span>
   								Lease Term
								<strong>11 Years 12 Months 8 weeks 10 days</strong>
   							</span>
   							<span>
   								Lease Expiring On
								<strong>2042-03-12</strong>
   							</span>
   							<span>
   								Undiscounted Lease Liability
								<strong>3066000</strong>
   							</span>
   						</div>
   					</li>
   					<li>
   						<div class="landType2">2BHK Residential...</div>
   						<div class="leaseterms">
   							<span>
   								Lease Term
								<strong>11 Years 12 Months 8 weeks 10 days</strong>
   							</span>
   							<span>
   								Lease Expiring On
								<strong>2042-03-12</strong>
   							</span>
   							<span>
   								Undiscounted Lease Liability
								<strong>3066000</strong>
   							</span>
   						</div>
   					</li>
   				</ul>
   			</div>
   		</div>
   </div>
@endsection
@section('footer-script')

@endsection