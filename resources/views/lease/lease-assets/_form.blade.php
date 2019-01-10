
<form class="form-horizontal" method="post">
    {{ csrf_field() }}
    <div class="FrmOuterBx">
        <table class="table table-bordered table-responsive">
            <thead>
            <tr>
                <th width="7%">Sr. No.</th>
                <th width="15%">Unique ULA Code</th>
                <th width="15%">Underlying Lease Asset Category</th>
                <th width="15%">Underlying Leased Asset Classification</th>
                <th width="15%">Name of the Underlying Lease Asset</th>
                <th width="20%">Number of Units of Lease Assets of Similar Characteristics</th>
                <th width="13%">Action</th>
            </tr>
            </thead>
            <tbody>

            @php
                $completed_asset_details = 0;
            @endphp

            @for($i = 0; $i < $lease->total_assets; $i++)
                <tr>
                    <td>{{ $i + 1 }}</td>

                    @if(isset($lease_assets[$i]))
                        @php
                            if($lease_assets[$i]['is_details_completed'] == '1') {
                                $completed_asset_details++;
                            }
                        @endphp
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
                            @if($lease_assets[$i]['is_details_completed'] == '1')
                                <a href="{{ route('addlease.leaseasset.completedetails', ['lease' => $lease->id, 'asset' => $lease_assets[$i]['id']]) }}" class="btn btn-sm btn-primary">Modify Details</a>
                            @else
                                <a href="{{ route('addlease.leaseasset.completedetails', ['lease' => $lease->id, 'asset' => $lease_assets[$i]['id']]) }}" class="btn btn-sm btn-primary">Complete Details</a>
                            @endif
                        @endif
                    </td>

                </tr>
            @endfor
            </tbody>
        </table>
    </div>
    <div class="form-group">
        <div class="col-md-6 col-md-offset-5 frmBtnBx">

            <a href="{{ route('add-new-lease.index',['id' => $lease->id]) }}" class="btn btn-danger">Back</a>

            <button type="submit" class="btn btn-success">
                Submit
            </button>

            @if($lease->total_assets == $completed_asset_details)
                <a href="{{ route('addlease.payments.index',['id' => $lease->id]) }}" class="btn btn-primary">Next</a>
            @endif
        </div>
    </div>
</form>


