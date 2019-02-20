<div class="panel panel-default">
    <div class="panel-heading">Impairement Test Of Lease Asset</div>

    <div class="panel-body">
        @if (session('status1'))
            <div class="alert alert-success">
                {{ session('status1') }}
            </div>
        @endif

        <div class="tab-content" style="padding: 0px;">
            <div role="tabpanel" class="tab-pane active">
                <div class="panel panel-info">
                    <div class="panel-heading">Impairement Test Of Lease Asset</div>
                    <table class="table table-bordered table-responsive">
                        <thead>
                        <tr>
                    <th>Sr. No.</th>
                    <th> Lease Asset </th>
                    <th> LA Classification</th>
                    <th>Value of Lease Asset</th>
                    <th> FM value Lease Asset</th>
                    <th>Impairment, if any</th>
                        </tr>
                        </thead>
                       <tbody>
            @php
                $show_next = [];
            @endphp
            @foreach($assets as $key=>$asset)
                <tr>
                    <td>{{ $key + 1 }}</td>
                   
                    <td>
                        {{ $asset->name }}
                    </td>
                    <td>
                        {{ $asset->subcategory->title }}
                    </td>
                    <td class="value_of_lease_asset" data-asset_id="{{ $asset->id }}"></td>
                    @if($asset->fairMarketValue)
                        @if($asset->fairMarketValue->is_market_value_present == "yes")
                            <td>{{ number_format($asset->fairMarketValue->total_units, 2) }}</td>
                        @else
                            <td>0</td>
                        @endif
                    @else
                        <td>0</td>
                    @endif
                    <td class="impairment_if_any" data-asset_id="{{ $asset->id }}"></td>
                </tr>
            @endforeach
            </tbody>
                    </table>
                </div>

               
            </div>
        </div>
    </div>
</div>