@foreach($final_data as $month=>$datum)
    <div class="row">
        <div class="col-md-2" style="background-color: #F2F2F2;padding: 22px;border: 1px solid;border-radius: 3px;">
            {{ $month }}
        </div>
        <div class="col-md-1">&nbsp;</div>
        <div class="col-md-9" style="background-color: #cccccc40;margin-bottom: 10px;">
            @foreach($datum as $dates)
                <div class="col-md-1" style="margin: 4px;padding: 0px;border: 1px solid;border-radius: 5px;">
                    <div style="background-color: rgba(168,162,165,0.38);text-align: center">
                        {{ \Carbon\Carbon::parse($dates['date'])->format('d') }}
                    </div>
                    <div style="background-color: rgba(80,187,8,0.38);text-align: center">
                        {{ number_format($dates['rate']) }}
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endforeach