<div class="container">
    <div class="breadcrumbs">
        @foreach($docs as $doc)
            @if(!$loop->last)
                <a href="{{ $doc['url'] }}">{{ $doc['pagetitle'] }}</a>
                <i class="fas fa-chevron-right"></i>
            @else
                <span class="current">{{ $doc['pagetitle'] }}</span>
            @endif
        @endforeach
    </div>
</div>