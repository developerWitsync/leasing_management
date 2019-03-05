@if (!empty($errors))
    <div class="alert alert-danger">
        <ul>
            @foreach (array_unique($errors->all()) as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@else
    <div class="row">
        <div class="col-md-12" style="overflow: auto">
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th>&nbsp;</th>
                    @foreach($years as $year)
                        <th colspan="2" style="text-align: center">
                            <strong>{{ $year }}</strong>
                        </th>
                    @endforeach
                </tr>
                <tr>
                    <th>&nbsp;</th>
                    @foreach($years as $year)
                        <th>
                            <strong>Date</strong>
                        </th>
                        <th> <strong>Payment</strong></th>
                    @endforeach
                </tr>
                </thead>
                <tbody>
                @foreach($months as $key=>$month)
                    <tr>
                        <td>
                            <strong>
                                {{ $month }}
                            </strong>
                        </td>
                        @foreach($years as $year)
                            @if(isset($dates[$year][str_pad($key, 2 , 0, STR_PAD_LEFT)]))
                                <td class="info">
                                        {{ $dates[$year][str_pad($key, 2 , 0, STR_PAD_LEFT)]['0']['date'] }}
                                </td>
                                <td class="info">
                                    <input type="text" name="inconsistent_date_payment[{{$dates[$year][str_pad($key, 2 , 0, STR_PAD_LEFT)]['0']['date']}}]" class="form-control" value="{{$dates[$year][str_pad($key, 2 , 0, STR_PAD_LEFT)]['0']['amount']}}">
                                </td>
                            @else
                                <td class="info"></td>
                                <td class="info"></td>
                            @endif
                        @endforeach
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endif