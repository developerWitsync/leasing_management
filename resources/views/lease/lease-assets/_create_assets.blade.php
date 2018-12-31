<form class="update_total_lease_assets" action="{{ route('add-new-lease.index.updatetotalassets', ['id' => $lease->id]) }}" method="post">
    {{ csrf_field() }}
    <div class="form-group required">
        <label for="no_of_lease_assets" class="col-md-4 control-label">Number of Underlying Lease Assets Involved</label>
        <div class="col-md-6">
            @if($lease->lease_type_id == 1)
                <select name="total_lease_assets" id="no_of_lease_assets" class="form-control">
                    <option value="">--Select Total Assets--</option>
                    <option value="1" @if($lease->total_assets == 1) selected="selected" @endif>1</option>
                </select>
            @else
                <select name="total_lease_assets" id="no_of_lease_assets" class="form-control">
                    <option value="">--Select Total Assets--</option>
                    @foreach($numbers_of_lease_assets as $numbers_of_lease_asset)
                        <option value="{{ $numbers_of_lease_asset->number }}" @if($numbers_of_lease_asset->number == $lease->total_assets) selected="selected" @endif>{{ $numbers_of_lease_asset->number }}</option>
                    @endforeach
                </select>
            @endif
        </div>
    </div>
</form>

@if($lease->total_assets > 0)
    @include('lease.lease-assets._form')
@endif