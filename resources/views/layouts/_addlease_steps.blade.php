 <div class="itemTab" >
                            <ul>
                                <li>
                                    <a class="@if(request()->segment(2) == 'lessor-details') active @endif" href="#">
                                        <img src="{{ asset('assets/images/breatcrum-icon.png') }}" class="img" alt="" >
                                        <img src="{{ asset('assets/images/breatcrum-icon-hover.png') }}" alt="" class="over" >
                                        <span>Lessor Details</span>
                                    </a>
                                </li>
                                <li>
                                    <a class="@if(request()->segment(2) == 'underlying-lease-assets') active @endif" href="#">
                                        <img src="{{ asset('assets/images/breatcrum-icon2.png') }}" class="img" alt="" >
                                        <img src="{{ asset('assets/images/breatcrum-icon2-hover.png') }}" alt="" class="over" >
                                        <span>Underlying Lease Asset</span>
                                    </a>
                                </li>
                                <li>
                                    <a class="@if(request()->segment(2) == 'payments') active @endif" href="#">
                                        <img src="{{ asset('assets/images/breatcrum-icon3.png') }}" class="img" alt="" >
                                        <img src="{{ asset('assets/images/breatcrum-icon3-hover.png') }}" alt="" class="over" >
                                        <span>Add Lease Payments</span>
                                    </a>
                                </li>
                                <li>
                                    <a  class="@if(request()->segment(2) == 'fair-market-value') active @endif" href="#">
                                        <img src="{{ asset('assets/images/breatcrum-icon4.png') }}" class="img" alt="" >
                                        <img src="{{ asset('assets/images/breatcrum-icon4-hover.png') }}" alt="" class="over" >
                                        <span>Fair Market Value</span>
                                    </a>
                                </li>
                                <li>
                                    <a  class="@if(request()->segment(2) == 'residual-value-gurantee') active @endif" href="#">
                                        <img src="{{ asset('assets/images/breatcrum-icon5.png') }}" class="img" alt="" >
                                        <img src="{{ asset('assets/images/breatcrum-icon5-hover.png') }}" alt="" class="over" >
                                        <span>Residual Value Guarantee</span>
                                    </a>
                                </li>
                                <li>
                                    <a class="@if(request()->segment(2) == 'lease-termination-option') active @endif" href="#">
                                        <img src="{{ asset('assets/images/breatcrum-icon6.png') }}" class="img" alt="" >
                                        <img src="{{ asset('assets/images/breatcrum-icon6-hover.png') }}" alt="" class="over" >
                                        <span>Termination Option</span>
                                    </a>
                                </li>
                                <li>
                                    <a  class="@if(request()->segment(2) == 'lease-renewal-option') active @endif" href="#">
                                        <img src="{{ asset('assets/images/breatcrum-icon7.png') }}" class="img" alt="" >
                                        <img src="{{ asset('assets/images/breatcrum-icon7-hover.png') }}" alt="" class="over" >
                                        <span>Renewal Option</span>
                                    </a>
                                </li>
                                <li>
                                    <a class="@if(request()->segment(2) == 'purchase-option') active @endif"  href="#">
                                        <img src="{{ asset('assets/images/breatcrum-icon8.png') }}" class="img" alt="" >
                                        <img src="{{ asset('assets/images/breatcrum-icon8-hover.png') }}" alt="" class="over" >
                                        <span>Purchase Option</span>
                                    </a>
                                </li>
                                <li>
                                    <a class="@if(request()->segment(2) == 'lease-duration-classified') active @endif" href="#">
                                        <img src="{{ asset('assets/images/breatcrum-icon9.png') }}" class="img" alt="" >
                                        <img src="{{ asset('assets/images/breatcrum-icon9-hover.png') }}" alt="" class="over" >
                                        <span>Lease Duration Classified</span>
                                    </a>
                                </li>
                                <li>
                                    <a class="@if(request()->segment(2) == 'escalation') active @endif" href="#">
                                        <img src="{{ asset('assets/images/breatcrum-icon10.png') }}" class="img" alt="" >
                                        <img src="{{ asset('assets/images/breatcrum-icon10-hover.png') }}" alt="" class="over" >
                                        <span>Lease Escalation</span>
                                    </a>
                                </li>
                                <li>
                                    <a class="@if(request()->segment(2) == 'select-low-value') active @endif" href="#">
                                        <img src="{{ asset('assets/images/breatcrum-icon11.png') }}" class="img" alt="" >
                                        <img src="{{ asset('assets/images/breatcrum-icon11-hover.png') }}" alt="" class="over" >
                                        <span>Select Low Value</span>
                                    </a>
                                </li>
                                <li>
                                    <a class="@if(request()->segment(2) == 'select-discount-rate') active @endif" href="#">
                                        <img src="{{ asset('assets/images/breatcrum-icon12.png') }}" class="img" alt="" >
                                        <img src="{{ asset('assets/images/breatcrum-icon12-hover.png') }}" alt="" class="over" >
                                        <span>Select Discount Rate</span>
                                    </a>
                                </li>
                                <li>
                                    <a class="@if(request()->segment(2) == 'lease-balnce-as-on-dec') active @endif" href="#">
                                        <img src="{{ asset('assets/images/breatcrum-icon13.png') }}" class="img" alt="" >
                                        <img src="{{ asset('assets/images/breatcrum-icon13-hover.png') }}" alt="" class="over" >
                                        <span>Lease Balances</span>
                                    </a>
                                </li>
                                <li>
                                    <a class="@if(request()->segment(2) == 'initial-direct-cost') active @endif" href="#">
                                        <img src="{{ asset('assets/images/breatcrum-icon14.png') }}" class="img" alt="" >
                                        <img src="{{ asset('assets/images/breatcrum-icon14-hover.png') }}" alt="" class="over" >
                                        <span>Initial Direct Cost</span>
                                    </a>
                                </li>
                                <li>
                                    <a  class="@if(request()->segment(2) == 'lease-incentives') active @endif"  href="#">
                                        <img src="{{ asset('assets/images/breatcrum-icon15.png') }}" class="img" alt="" >
                                        <img src="{{ asset('assets/images/breatcrum-icon15-hover.png') }}" alt="" class="over" >
                                        <span>Lease Incentives</span>
                                    </a>
                                </li>
                                <li>
                                    <a class="@if(request()->segment(2) == 'lease-valuation') active @endif" href="#">
                                        <img src="{{ asset('assets/images/breatcrum-icon16.png') }}" class="img" alt="" >
                                        <img src="{{ asset('assets/images/breatcrum-icon16-hover.png') }}" alt="" class="over" >
                                        <span>Lease Valuation</span>
                                    </a>
                                </li>
                                <li>
                                    <a  class="@if(request()->segment(2) == 'lease-payment-invoice') active @endif" href="#">
                                        <img src="{{ asset('assets/images/breatcrum-icon17.png') }}" class="img" alt="" >
                                        <img src="{{ asset('assets/images/breatcrum-icon17-hover.png') }}" alt="" class="over" >
                                        <span>Lessor Invoice</span>
                                    </a>
                                </li>
                                <li>
                                    <a class="@if(request()->segment(2) == 'review-submit') active @endif" href="#">
                                        <img src="{{ asset('assets/images/breatcrum-icon18.png') }}" class="img" alt="" >
                                        <img src="{{ asset('assets/images/breatcrum-icon18-hover.png') }}" alt="" class="over" >
                                        <span>Review & Submit</span>
                                    </a>
                                </li>

                            </ul>
                        </div>