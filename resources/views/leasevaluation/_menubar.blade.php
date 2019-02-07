<div>
    <ul class="nav nav-pills">
        <li role="presentation" @if(!request()->has('id')) class="active" @endif>
            <a href="{{ route('leasevaluation.index', ['capitalized' => $capitalized]) }}">Consolidated</a>
        </li>

        @foreach($categories as $key=>$value)
            <li role="presentation" @if(request()->has('id') && request()->id == $value->id) class="active" @endif>
                <a href="{{ route('leasevaluation.index',['capitalized' => $capitalized,'id' => $value->id ])}}">{{ $value->title }}</a>
            </li>
        @endforeach
    </ul>
</div>