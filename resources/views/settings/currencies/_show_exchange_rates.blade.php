<hr></hr>
<div class="container"><h3>Saved Exchange Rates</h3></div>

<div id="exTab2" class="container">
    <ul class="nav nav-tabs">
        @php
        $j = 1 ;
        @endphp
        @for($i = $start_year; $i <= $end_year; $i++)
            <li class="@if($j == 1) active @endif">
                <a href="javascript:void(0);" class="render_exchange_rates @if($j == 1) active @endif" data-year="{{ $i }}" data-f_cid="{{ $model->id }}" data-toggle="tab">{{ $i }}</a>
            </li>
            @php
                $j = $j + 1;
            @endphp
        @endfor
    </ul>

    <div class="tab-content append_here">
    </div>
</div>