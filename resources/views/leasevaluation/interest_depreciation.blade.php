@extends('layouts.app')
@section('header-styles')
    <link rel="stylesheet" href="{{ asset('css/jquery-ui.css') }}">
    <!-- BEGIN CSS for this page -->
    <link rel="stylesheet" type="text/css"
          href="{{ asset('assets/plugins/datatables/dataTables.bootstrap4.min.css') }}"/>
@endsection
@section('content')
    <div class="leasingModuleOuter">
        <div class="leasingMainHd clearfix">{{ $asset->name }} <span> {{ $asset->category->title }} </span>
        </div>
        <div class="assetsNameOuter">
            <div class="assetsTabs">
                <ul>
                    @if(request()->segment(2) == 'valuation-capitalised')
                        <li>
                            <a href="{{ route('leasevaluation.cap.asset', ['id' => $lease->id]) }}">Overivew</a>
                        </li>

                        <li>
                            <a href="{{ route('leasevaluation.cap.asset.valuation', ['id' => $lease->id]) }}">Valuation</a>
                        </li>

                        <li>
                            <a href="{{ route('leasevaluation.cap.interestdepreciation', ['id' => $lease->id]) }}" class="active">Interest &amp; Depreciation</a>
                        </li>
                    @else
                        <li>
                            <a href="{{ route('leasevaluation.ncap.asset', ['id' => $lease->id]) }}" class="active">Overivew</a>
                        </li>
                    @endif
                </ul>
            </div>

            <div class="tabBxOuter" style="display: block;" id="assetTab3">
                <div class="leaseRafBx">
                    <div class="initialGraphBx">
                        <img src="/assets/images/initialGraph2.png">
                    </div>
                </div>

                <!--Lease Valuation-->
                <div class="locatPurposeOutBx">
                    <div class="locatpurposeTop leaseterminatHd">
                        Lease Interest Expense
                    </div>
                    <div class="leasepaymentTble">
                        <table cellpadding="0" cellspacing="0" border="0" width="100%">
                            <tr>
                                <th width="8.5%">Year</th>
                                <th width="14.5%">
                                    <span style="border-bottom:2px dashed #666; padding: 3px 0; display: block;">Lease Start Date</span>
                                    <span style="border-bottom:2px dashed #666; padding: 3px 0; display: block;">Lease Payment Dates</span>
                                    <span>Month End Dates</span>
                                </th>
                                <th width="10%">Number of Days</th>
                                <th width="12%">Effective Daily <br/> Discount Rate</th>
                                <th width="51%" style="padding: 0px;">
                                    <table cellspacing="0" cellpadding="0" border="0" width="100%" class="tableInner">
                                        <tr>
                                            <th class="leasevaluaTh2" colspan="5" style="padding-bottom:0px; border-bottom: 0px;"><span style="text-align: center; border-bottom: #cccfd9 solid 1px; display: block; padding-bottom: 5px;">Lease Currency - Specify Currency</span></th>
                                        </tr>
                                        <tr>
                                            <th style="border-bottom:none; text-align: center;" width="25%">Opening <br/> Lease Liability </th>
                                            <th style="border-bottom:none; text-align: center;" width="27%">Monthly <br/> Interest Expense</th>
                                            <th style="border-bottom:none; text-align: center;" width="23%">Lease <br/> Payments</th>
                                            <th style="border-bottom:none; text-align: center;" width="25%">Closing <br/> Lease Liability</th>
                                        </tr>
                                    </table>
                                </th>
                            </tr>
                            @php
                                $i = 1;
                            @endphp
                            @foreach($interest_depreciation as $modify_id=>$details)
                                <tr bgcolor="#117bb8">
                                    <td colspan="5" style="padding: 0;">
                                        <table cellpadding="0" cellspacing="0" border="0" class="categoryTble">
                                            <tr>
                                                <td>Part {{$i}}:</td>

                                                    @if($modify_id == "")
                                                    <td colspan="4">
                                                        Initial Valuation Basis
                                                    </td>
                                                    @else
                                                        <td>Subsequent Lease Valuation</td>
                                                        <td>Subsequent Reference# {{$i}}</td>
                                                        <td>Effective from</td>
                                                        <td>DD/MM/YYYY</td>
                                                    @endif
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                @foreach($details as $detail)
                                    <tr>
                                        <td>{{\Carbon\Carbon::parse($detail->date)->format('Y')}}</td>
                                        <td>{{\Carbon\Carbon::parse($detail->date)->format(config('settings.date_format'))}}</td>
                                        <td>{{$detail->number_of_days}}</td>
                                        <td>{{$detail->discount_rate}}</td>
                                        <td style="padding: 0px;">
                                            <table cellspacing="0" cellpadding="0" border="0" width="100%" class="tableInner">
                                                <tr>
                                                    <td width="25%" class="blueClr" align="center">{{$detail->opening_lease_liability}}</td>
                                                    <td width="25%" class="blueClr" align="center">{{$detail->interest_expense}}</td>
                                                    <td width="25%" align="center">{{$detail->lease_payment}}</td>
                                                    <td width="25%" class="blueClr" align="center">{{ $detail->closing_lease_liability }}</td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                @endforeach
                                @php
                                    $i += 1;
                                @endphp
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>


        </div>
    </div>

@endsection
@section('footer-script')
    <script src="{{ asset('assets/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables/dataTables.bootstrap4.min.js') }}"></script>
@endsection