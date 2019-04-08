
    <div class="landBg clearfix">
        <div class="leaseTotalHd">
            <h2>{{ $category->title}}</h2>

            <span>Total Lease <br/> Assets</span>
            <strong>{{ str_pad($assets->total(), 2, '0', STR_PAD_LEFT) }}</strong>
            @if($assets->total() > 4)
                <div class="pagerButton">
                    {{ $assets->links('leasevaluation.partials._paginate', ['category' => $category->id]) }}
                </div>

            @endif
        </div>
        <div class="leaseSlide">
            <ul class="clearfix">
                @foreach($assets as $asset)
                    <li>
                        <div class="landType">{{str_limit($asset->name, $limit = 15, $end = '...')  }}</div>
                        <div class="leaseterms">
   							<span>
   								Lease Term
								<strong>
                                    {{ $asset->lease_term }}
                                </strong>
   							</span>
                            <span>
   								Lease Expiring On
								<strong>
                                    {{ \Carbon\Carbon::parse($asset->getLeaseEndDate($asset))->format('Y-m-d') }}
                                </strong>
   							</span>
                            <span>
   								Undiscounted Lease Liability
								<strong>
                                    {{ number_format($asset->leaseSelectLowValue->undiscounted_lease_payment, 2) }}
                                </strong>
   							</span>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
