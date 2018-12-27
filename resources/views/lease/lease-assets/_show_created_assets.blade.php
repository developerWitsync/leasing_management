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
                <td>{{ $i + 1 }}</td>

                @if(isset($lease_assets[$i]))
                    <td style="width: 10%">
                        <input type="hidden" name="ula_code[{{$i}}]" value="{{ $lease_assets[$i]['uuid'] }}">
                        {{ $lease_assets[$i]['uuid'] }}
                    </td>
                @else
                    <td style="width: 10%">
                        @php
                            $ula_code = \Webpatser\Uuid\Uuid::generate(1);
                        @endphp
                        <input type="hidden" name="ula_code[{{$i}}]" value="{{ $ula_code }}">
                        {{ $ula_code }}
                    </td>
                @endif

                <td>
                    @if(isset($lease_assets[$i]))
                        <select name="asset_category[{{$i}}]" class="form-control asset_category" data-number="{{ $i }}">
                            <option value="">--Select--</option>
                            @foreach($lease_assets_categories as $category)
                                <option value="{{ $category->id }}" @if($lease_assets[$i]['category_id'] == $category->id) selected="selected" @endif>{{ $category->title }}</option>
                            @endforeach
                        </select>
                    @else
                        <select name="asset_category[{{$i}}]" class="form-control asset_category" data-number="{{ $i }}">
                            <option value="">--Select--</option>
                            @foreach($lease_assets_categories as $category)
                                <option value="{{ $category->id }}">{{ $category->title }}</option>
                            @endforeach
                        </select>
                    @endif
                </td>
                <td>
                    @if(isset($lease_assets[$i]))
                        <select name="asset_sub_category[{{$i}}]" class="form-control asset_sub_category_{{$i}}">
                            <option value="">--Select--</option>
                            @foreach($lease_assets_categories as $category)
                                @if($category->id == $lease_assets[$i]['category_id'])
                                    @foreach($category->subcategories as $subcategory)
                                        <option value="{{ $subcategory->id }}" @if($subcategory->id == $lease_assets[$i]['sub_category_id']) selected="selected" @endif>{{ $subcategory->title }}</option>
                                    @endforeach
                                @endif
                            @endforeach
                        </select>
                    @else
                        <select name="asset_sub_category[{{$i}}]" class="form-control asset_sub_category_{{$i}}">
                            <option value="">--Select--</option>
                        </select>
                    @endif
                </td>
                <td>
                    @if(isset($lease_assets[$i]))
                        <input type="text" class="form-control" value="{{ $lease_assets[$i]['name'] }}" name="name[{{$i}}]" placeholder="Name">
                    @else
                        <input type="text" class="form-control" name="name[{{$i}}]" placeholder="Name">
                    @endif
                </td>
                <td>
                    @if(isset($lease_assets[$i]))
                        <select name="similar_characteristic_items[{{$i}}]" class="form-control">
                            <option value="">--Select--</option>
                            @foreach($la_similar_charac_number as $number)
                                <option value="{{ $number->number }}" @if($lease_assets[$i]['similar_asset_items']== $number->number) selected="selected" @endif>{{ $number->number }}</option>
                            @endforeach
                        </select>
                    @else
                        <select name="similar_characteristic_items[{{$i}}]" class="form-control">
                            <option value="">--Select--</option>
                            @foreach($la_similar_charac_number as $number)
                                <option value="{{ $number->number }}">{{ $number->number }}</option>
                            @endforeach
                        </select>
                    @endif
                </td>
                <td>
                    @if(isset($lease_assets[$i]))
                        <a href="{{ route('addlease.leaseasset.completedetails', ['lease' => $lease->id, 'asset' => $lease_assets[$i]['id']]) }}" class="btn btn-sm btn-primary">Complete Details</a>
                    @endif
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