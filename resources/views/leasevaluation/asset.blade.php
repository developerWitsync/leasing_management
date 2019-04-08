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
        <div class="leasingMainHd clearfix">Lease Asset Name <span>Lease Asset Category <select name="listmenu"><option>Select</option></select></span></div>
        <div class="assetsNameOuter">
        	<div class="assetsTabs">
        		<ul>
        			<li><a href="assetTab1">Overivew</a></li>
        			<li><a href="assetTab2">Valuation</a></li>
        			<li><a href="assetTab3">Interest &amp; Depreciation</a></li>
        		</ul>
        	</div>
        	<div class="tabBxOuter" style="display: none;" id="assetTab1">
        		<div class="leaseRafBx">
        			<div class="leaserefouter clearfix">
        				<div class="leaseref_left">
        					<span>Lease Reference Number</span>
        					<strong>29756825989946</strong>
        				</div>
        				<div class="leaseref_left">
        					<span>Valuation Basis</span>
        					<strong>
        						<i>
	        						<input type="checkbox" name="checkbox">
	        						<label>Initial</label>
	        					</i>
	        					<i>
	        						<input type="checkbox" name="checkbox">
	        						<label>Subsequent</label>
	        					</i>
        					</strong>
        				</div>
        			</div>
        			<div class="lessernameTop clearfix">
        				<div class="leasernameBx1 lesserName">
        					<span>Lessor Name</span>
        					<strong>John Smith</strong>
        				</div>
        				<div class="leasernameBx2 lesserName">
        					<span>Lease Classification</span>
        					<strong>102,02</strong>
        				</div>
        				<div class="leasernameBx2 lesserName">
        					<span>Lease Currency (LC)</span>
        					<strong>USD</strong>
        				</div>
        				<div class="leasernameBx2 lesserName">
        					<span>Units of Similar Characteristics</span>
        					<strong>262</strong>
        				</div>
        				<div class="leasernameBx2 lesserName">
        					<span>Lease Contract Start Date</span>
        					<strong>28-3-2019</strong>
        				</div>
        			</div>
        			<div class="clearfix">
        				<div class="leasernameBx1 lesserName">
        					<span>Initial Lease Free Period</span>
        					<strong>11 Years 8 months 2 weeks  4 days</strong>
        				</div>
        				<div class="leasernameBx2 lesserName">
        					<span>Effective Lease Start Date</span>
        					<strong>28-3-2019</strong>
        				</div>
        				<div class="leasernameBx2 lesserName">
        					<span>Lease Contract End Date</span>
        					<strong>28-3-2019</strong>
        				</div>
        				<div class="leasernameBx2 lesserName">
        					<span>Expected Lease End Date</span>
        					<strong>28-3-2019</strong>
        				</div>
        				<div class="leasernameBx2 lesserName">
        					<span>Lease Term</span>
        					<strong>11 Years</strong>
        				</div>
        			</div>
        		</div>
        		<!--Lease Locations-->
        		<div class="locatPurposeOutBx">
	        		<div class="locatpurposeTop clearfix">
	        			<div class="locatpurposeBx">Location</div>
	        			<div class="locatpurposeBx1">Purpose</div>
	        			<div class="locatpurposeBx1">Expected Useful Life</div>
	        			<div class="locatpurposeBx1">Leases Prior to 2019</div>
	        			<div class="locatpurposeBx1">Lessor Invoice</div>
	        		</div>
	        		<div class="locatpurposeBot clearfix">
	        			<div class="leasernameBx1 leaserLocation">
	        				<span>Country : <strong>India</strong></span>
							<span>City : <strong>Noida</strong></span>
	        			</div>
	        			<div class="leasernameBx2 leaserLocation">
	        				<span>Own / Sub-Lease : <strong></strong></span>
							<span>Reasons : <strong></strong></span>
	        			</div>
	        			<div class="leasernameBx2 leaserLocation">
	        				<span>Useful Life : <strong></strong></span>
	        			</div>
	        			<div class="leasernameBx2 leaseprior">
	        				<span>Accounting Treatmen
	        					<strong>Operating Lease Accounting</strong>
	        				</span>
	        				<span>Lease Payment Basis
	        					<strong>Current Lease Payment as onJanuary 01, 2019</strong>
	        				</span>
	        			</div>
	        			<div class="leasernameBx2 leaserLocation">
	        				<span>Useful Life : <strong></strong></span>
	        			</div>
	        		</div>
	        	</div>
	        	<!--Lease Termination-->
	        	<div class="locatPurposeOutBx">
	        		<div class="locatpurposeTop leaseterminatHd">
	        			Lease Termination Option
	        		</div>
	        		<div class="locatpurposeBot clearfix">
	        			<div class="leasernameBx1 leaseprior">
	        				<span>Termination Option Available Under the Contract
	        					<strong>Yes</strong>
	        				</span>
	        			</div>
	        			<div class="leasernameBx2 leaseprior">
	        				<span>Reasonable Certainity to Exercise Termination Option as of today
	        					<strong>Yes</strong>
	        				</span>
	        			</div>
	        			<div class="leasernameBx2 leaseprior">
	        				<span>Expected Lease End Date
	        					<strong>20-4-2019</strong>
	        				</span>
	        			</div>
	        			<div class="leasernameBx2 leaseprior">
	        				<span>Currency
	        					<strong>USD</strong>
	        				</span>
	        			</div>
	        			<div class="leasernameBx2 leaseprior">
	        				<span>Termination Penalty
	        					<strong>$250.00</strong>
	        				</span>
	        			</div>
	        		</div>
	        	</div>


	        	<!--Lease Renewal-->
	        	<div class="locatPurposeOutBx">
	        		<div class="locatpurposeTop leaseterminatHd">
	        			Lease Renewal Option
	        		</div>
	        		<div class="locatpurposeBot clearfix">
	        			<div class="leaserrenewalBx1 leaseprior">
	        				<span>Renewal Option Available Under the Contract
	        					<strong>Yes</strong>
	        				</span>
	        			</div>
	        			<div class="leaserrenewalBx2 leaseprior">
	        				<span>Reasonable Certainity to Exercise Termination Option as of today
	        					<strong>Yes</strong>
	        				</span>
	        			</div>
	        			<div class="leaserrenewalBx2 leaseprior">
	        				<span>Expected Lease End Date
	        					<strong>20-4-2019</strong>
	        				</span>
	        			</div>
	        		</div>
	        	</div>

	        	<!--Lease Purchase-->
	        	<div class="locatPurposeOutBx">
	        		<div class="locatpurposeTop leaseterminatHd">
	        			Lease Purchase Option
	        		</div>
	        		<div class="locatpurposeBot clearfix">
	        			<div class="leaserrenewalBx1 leaseprior">
	        				<span>Purchase Option Available Under the Contract
	        					<strong>Yes</strong>
	        				</span>
	        				<span>Expected Lease End Date
	        					<strong>20-5-2019</strong>
	        				</span>
	        			</div>
	        			<div class="leaserrenewalBx2 leaseprior">
	        				<span>Reasonable Certainity to Exercise Purchase Option as of today
	        					<strong>Yes</strong>
	        				</span>
	        				<span>Currency
	        					<strong>USD</strong>
	        				</span>
	        			</div>
	        			<div class="leaserrenewalBx2 leaseprior">
	        				<span>Expected Purchase Date
	        					<strong>20-4-2019</strong>
	        				</span>
	        				<span>Purchase Price
	        					<strong>$250.00</strong>
	        				</span>
	        			</div>
	        		</div>
	        	</div>

	        	<!--Lease Purchase-->
	        	<div class="locatPurposeOutBx">
	        		<div class="locatpurposeTop leaseterminatHd">
	        			Lease Purchase Option
	        		</div>
	        		<div class="locatpurposeBot clearfix">
	        			<div class="leaserrenewalBx1 leaseprior">
	        				<span>Purchase Option Available Under the Contract
	        					<strong>Yes</strong>
	        				</span>
	        				<span>Expected Lease End Date
	        					<strong>20-5-2019</strong>
	        				</span>
	        			</div>
	        			<div class="leaserrenewalBx2 leaseprior">
	        				<span>Reasonable Certainity to Exercise Purchase Option as of today
	        					<strong>Yes</strong>
	        				</span>
	        				<span>Currency
	        					<strong>USD</strong>
	        				</span>
	        			</div>
	        			<div class="leaserrenewalBx2 leaseprior">
	        				<span>Expected Purchase Date
	        					<strong>20-4-2019</strong>
	        				</span>
	        				<span>Purchase Price
	        					<strong>$250.00</strong>
	        				</span>
	        			</div>
	        		</div>
	        	</div>


	        	<!--Lease Payments-->
	        	<div class="locatPurposeOutBx">
	        		<div class="locatpurposeTop leaseterminatHd">
	        			Lease Payments
	        		</div>
	        		<div class="leasepaymentTble">
	        			<table cellpadding="0" cellspacing="0" border="0" width="100%">
	        				<tr>
	        					<th width="16.66%">Lease Payments</th>
	        					<th width="16.66%">Type</th>
	        					<th width="16.66%">Nature</th>
	        					<th width="16.66%">LP Interval</th>
	        					<th width="16.66%">Interval Point</th>
	        					<th width="16.66%">First Lease Payment <br/>Start Date</th>
	        				</tr>
	        				<tr>
	        					<td>Basic Rent</td>
	        					<td>Lease <br/> Component </td>
	        					<td>Fixed Lease <br/> Payment</td>
	        					<td>Monthly</td>
	        					<td>At Lease <br/> Interval End</td>
	        					<td>Date</td>
	        				</tr>
	        				<tr>
	        					<td>Basic Rent</td>
	        					<td>Lease <br/> Component </td>
	        					<td>Fixed Lease <br/> Payment</td>
	        					<td>Monthly</td>
	        					<td>At Lease <br/> Interval End</td>
	        					<td>Date</td>
	        				</tr>
	        			</table>
	        		</div>
	        	</div>


	        	<!--Lease Payments-->
	        	<div class="locatPurposeOutBx">
	        		<div class="locatpurposeTop leaseterminatHd">
	        			Lease Escalation
	        		</div>
	        		<div class="leasepaymentTble">
	        			<table cellpadding="0" cellspacing="0" border="0" width="100%">
	        				<tr>
	        					<th width="16.66%">Lease Payments</th>
	        					<th width="16.66%">Lease Escalation Applied</th>
	        					<th width="16.66%">Escalation Effective Date</th>
	        					<th width="16.66%">Escalation Basis</th>
	        					<th width="16.66%">Escalation Consitently<br/>Applied</th>
	        					<th width="16.66%">Escalation <br/> Fixed Rate</th>
	        				</tr>
	        				<tr>
	        					<td>Basic Rent</td>
	        					<td>Yes</td>
	        					<td>20-05-2019</td>
	        					<td>10% of $250</td>
	        					<td>Yes</td>
	        					<td>10%</td>
	        				</tr>
	        				<tr>
	        					<td>Basic Rent</td>
	        					<td>Yes</td>
	        					<td>20-05-2019</td>
	        					<td>10% of $250</td>
	        					<td>Yes</td>
	        					<td>10%</td>
	        				</tr>
	        				<tr>
	        					<td>Basic Rent</td>
	        					<td>Yes</td>
	        					<td>20-05-2019</td>
	        					<td>10% of $250</td>
	        					<td>Yes</td>
	        					<td>10%</td>
	        				</tr>
	        			</table>
	        		</div>
	        	</div>

	        	<!--Residual Value Guarantee-->
	        	<div class="locatPurposeOutBx">
	        		<div class="locatpurposeTop leaseterminatHd">
	        			Residual Value Guarantee
	        		</div>
	        		<div class="locatpurposeBot clearfix">
	        			<div class="leaserrenewalBx1 leaseprior">
	        				<span>Residual Value Guarantee Applicable
	        					<strong>Yes</strong>
	        				</span>
	        				<span>Currency
	        					<strong>USD</strong>
	        				</span>
	        			</div>
	        			<div class="leaserrenewalBx2 leaseprior">
	        				<span>Variable Basis 		
	        					<strong>Turnover Lease</strong>
	        				</span>
	        				<span>Residual Value
	        					<strong>123</strong>
	        				</span>
	        			</div>
	        			<div class="leaserrenewalBx2 leaseprior">
	        				<span>Determinable
	        					<strong>Yes</strong>
	        				</span>
	        				<span>Amount
	        					<strong>$250.00</strong>
	        				</span>
	        			</div>
	        		</div>
	        	</div>

	        	<!--Discounting Rate-->
	        	<div class="locatPurposeOutBx">
	        		<div class="locatpurposeTop leaseterminatHd">
	        			Discounting Rate
	        		</div>
	        		<div class="discountRateOuter">
	        			<div class="discountTop clearfix">
	        				<div class="discountBx">
	        					<span>Implicit Interest Rate</span>
	        					<strong>30%</strong>
	        				</div>
	        				<div class="discountBx">
	        					<span>Annual Avg. Escalation Rate</span>
	        					<strong>50%</strong>
	        				</div>
	        				<div class="discountBx">
	        					<span>Discount Rate in Use</span>
	        					<strong>10%</strong>
	        				</div>
	        				<div class="discountBx">
	        					<span>Effective Daily Discount Rate</span>
	        					<strong>20%</strong>
	        				</div>
	        			</div>
	        			<div class="graphBx"><img src="../assets/images/graph-img.png" alt=""></div>
	        		</div>
	        	</div>

	        	<!--Lease Security Deposit-->
	        	<div class="locatPurposeOutBx">
	        		<div class="locatpurposeTop leaseterminatHd">
	        			Lease Security Deposit
	        		</div>
	        		<div class="locatpurposeBot clearfix">
	        			<div class="leaserrenewalBx1 leaseprior">
	        				<span>Any Security Deposit
	        					<strong>Yes</strong>
	        				</span>
	        				<span>Type of Secuity
	        					<strong>Money Transfer</strong>
	        				</span>
	        			</div>
	        			<div class="leaserrenewalBx2 leaseprior">
	        				<span>Security Deposit Refundable <br/> or Adjustable ? 		
	        					<strong>Refundable</strong>
	        				</span>
	        				<span>Currency
	        					<strong>USD</strong>
	        				</span>
	        			</div>
	        			<div class="leaserrenewalBx2 leaseprior">
	        				<span>Date of Payment
	        					<strong>20-03-2019</strong>
	        				</span>
	        				<span>Value 
	        					<strong>$250.00</strong>
	        				</span>
	        			</div>
	        		</div>
	        	</div>
        	</div>

        	<div class="tabBxOuter" id="assetTab2">
        		<div class="leaseRafBx">
        			
        			<div class="initialGraphBx">
        				<img src="/assets/images/initialGraph.png">
        			</div>
        		</div>
        		<!--Lease Valuation-->
        		<div class="locatPurposeOutBx">
	        		<div class="locatpurposeTop leaseterminatHd">
	        			Lease Valuation
	        		</div>
	        		<div class="leasepaymentTble">
	        			<table cellpadding="0" cellspacing="0" border="0" width="100%">
	        				<tr>
	        					<th width="6%">S No.</th>
	        					<th width="11%">Effective Date</th>
	        					<th width="13%">Valuation Type</th>
	        					<th width="16%">Discount Rate Applied %</th>
	        					<th width="54%" style="padding: 0px;">
	        						<table cellspacing="0" cellpadding="0" border="0" width="100%" class="tableInner">
	        							<tr>
	        								<th class="leasevaluaTh2" colspan="5" style="padding-bottom:0px;"><span style="text-align: center; border-bottom: #cccfd9 solid 1px; display: block; padding-bottom: 5px;">Lease Currency - Specify Currency</span></th>
	        							</tr>
	        							<tr>
	        								<th style="border-bottom:none" width="22%">UD Lease Liability</th>
				        					<th style="border-bottom:none" width="22%">PV of Lease Liability</th>
				        					<th style="border-bottom:none" width="22%">Value of Lease Asset</th>
				        					<th style="border-bottom:none" width="17%">Fair Value</th>
				        					<th style="border-bottom:none" width="17%">Impairement</th>
	        							</tr>
	        						</table>
	        					</th>
	        				</tr>
	        				<tr>
	        					<td>1</td>
	        					<td>20-05-2019 </td>
	        					<td>Initial Valuation</td>
	        					<td></td>
	        					<td style="padding: 0px;">
	        						<table cellspacing="0" cellpadding="0" border="0" width="100%" class="tableInner">
	        							<tr>
	        								<td width="22%" class="blueClr">2,344.00</td>
	        								<td width="22%" class="blueClr">2,100.00</td>
	        								<td width="22%" class="blueClr">2,100.00</td>
	        								<td width="17%"></td>
	        								<td width="17%"></td>
	        							</tr>
	        						</table>
	        					</td>
	        				</tr>
	        				<tr>
	        					<td>2</td>
	        					<td>20-05-2019</td>
	        					<td>Subsequent Valuation</td>
	        					<td></td>
	        					<td style="padding: 0px;">
	        						<table cellspacing="0" cellpadding="0" border="0" width="100%" class="tableInner">
	        							<tr>
	        								<td width="22%"></td>
	        								<td width="22%"></td>
	        								<td width="22%"></td>
	        								<td width="17%"></td>
	        								<td width="17%"></td>
	        							</tr>
	        						</table>
	        					</td>
	        				</tr>
	        				<tr>
	        					<td>3</td>
	        					<td>20-05-2019</td>
	        					<td>Subsequent Valuation</td>
	        					<td></td>
	        					<td style="padding: 0px;">
	        						<table cellspacing="0" cellpadding="0" border="0" width="100%" class="tableInner">
	        							<tr>
	        								<td width="22%"></td>
	        								<td width="22%"></td>
	        								<td width="22%"></td>
	        								<td width="17%"></td>
	        								<td width="17%"></td>
	        							</tr>
	        						</table>
	        					</td>
	        				</tr>
	        			</table>
	        		</div>
	        	</div>
	        	
	        	<!--Present Value-->
	        	<div class="presentValue">
	        		<ul class="clearfix">
	        			<li>
	        				<span>Present Value of Lease Liability</span>
	        				<strong>INR 240,000</strong>
	        			</li>
	        			<li>
	        				<span>Lease Balances as on Dec 31, 2018</span>
	        				<strong>INR 1,227</strong>
	        			</li>
	        			<li>
	        				<span>Initial Direct Cost</span>
	        				<strong>INR 1,284</strong>
	        			</li>
	        			<li>
	        				<span>Lease Incentives</span>
	        				<strong>INR 1110</strong>
	        			</li>
	        			<li>
	        				<span>Estimated Dismantling Costs</span>
	        				<strong>INR 1110</strong>
	        			</li>
	        			<li>
	        				<span>Value of Lease Asset</span>
	        				<strong>INR 1110</strong>
	        			</li>
	        		</ul>
	        	</div>



        </div>
    </div>
@endsection
@section('footer-script')

@endsection