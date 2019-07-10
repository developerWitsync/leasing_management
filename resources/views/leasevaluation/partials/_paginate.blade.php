@if ($paginator->hasPages())
    {{-- Previous Page Link --}}
    @if ($paginator->onFirstPage())
        <a href="javascript:void(0);" class="disabled">
            <img src="{{ asset('assets/images/land-arrow-left1.png') }}" class="img">
            <img src="{{ asset('assets/images/land-arrow-left.png') }}" class="over">
        </a>
    @else
        <a href="{{ $paginator->previousPageUrl() }}" class="pagination_changed" data-category="{{$category}}">
            <img src="{{ asset('assets/images/land-arrow-left1.png') }}" class="img">
            <img src="{{ asset('assets/images/land-arrow-left.png') }}" class="over">
        </a>
    @endif


    {{-- Next Page Link --}}
    @if ($paginator->hasMorePages())
        <a href="{{ $paginator->nextPageUrl() }}" class="pagination_changed" data-category="{{$category}}">
            <img src="{{ asset('assets/images/land-arrow-right1.png') }}" class="img">
            <img src="{{ asset('assets/images/land-arrow-right.png') }}" class="over">
        </a>
    @else
        <a href="javascript:void(0);" class="disabled">
            <img src="{{ asset('assets/images/land-arrow-right1.png') }}" class="img">
            <img src="{{ asset('assets/images/land-arrow-right.png') }}" class="over">
        </a>
    @endif
@endif