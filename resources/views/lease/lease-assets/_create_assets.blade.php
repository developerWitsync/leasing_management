<form class="form-horizontal" method="post">
    {{ csrf_field() }}
    <div class="form-group required">
        <label for="no_of_lease_assets" class="col-md-4 control-label">Number of Underlying Lease Assets Involved</label>
        <div class="col-md-6">
            @if($lease->lease_type_id == 1)
                <select name="total_lease_assets" id="no_of_lease_assets" class="form-control">
                    <option value="1" selected="selected">1</option>
                </select>

            @else
                <select name="total_lease_assets" id="no_of_lease_assets" class="form-control">
                    @foreach($numbers_of_lease_assets as $numbers_of_lease_asset)
                        <option value="{{ $numbers_of_lease_asset->number }}" @if($numbers_of_lease_asset->number == $total_number_of_assets) selected="selected" @endif>{{ $numbers_of_lease_asset->number }}</option>
                    @endforeach
                </select>
            @endif
        </div>
    </div>

    <table class="table table-bordered table-responsive">
        <thead>
        <tr>
            <th>Sr. No.</th>
            <th>Unique ULA Code</th>
            <th>Underlying Lease Asset Category</th>
            <th>Underlying Leased Asset Classification</th>
            <th>Name of the Underlying Lease Asset</th>
            <th>Number of Units of Lease Assets of Similar Characteristics</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        @for($i = 0; $i < $total_number_of_assets; $i++)
            <tr>
                <td>{{ $i }}</td>
                <td style="width: 10%">
                    @php
                        $ula_code = \Webpatser\Uuid\Uuid::generate(1);
                    @endphp
                    <input type="hidden" name="ula_code[{{$i}}]" value="{{ $ula_code }}">
                    {{ $ula_code }}
                </td>
                <td>
                    <select name="asset_category[{{$i}}]" class="form-control asset_category" data-number="{{ $i }}">
                        <option value="">--Select--</option>
                        @foreach($lease_assets_categories as $category)
                            <option value="{{ $category->id }}">{{ $category->title }}</option>
                        @endforeach
                    </select>
                </td>
                <td>
                    <select name="asset_sub_category[{{$i}}]" class="form-control asset_sub_category_{{$i}}">
                        <option value="">--Select--</option>
                    </select>
                </td>
                <td>
                    <input type="text" class="form-control" name="name[{{$i}}]" placeholder="Name">
                </td>
                <td>
                    <select name="similar_characteristic_items[{{$i}}]" class="form-control">
                        <option value="">--Select--</option>
                        @foreach($la_similar_charac_number as $number)
                            <option value="{{ $number->number }}">{{ $number->number }}</option>
                        @endforeach
                    </select>
                </td>
                <td>
                    &nbsp;
                </td>
            </tr>
        @endfor
        </tbody>
    </table>
    <div class="form-group">
        <div class="col-md-6 col-md-offset-4">
            <button type="submit" class="btn btn-success">
                Submit
            </button>
        </div>
    </div>
</form>