<div class="breadcrumb-navi">
    @if ($links)
        <ol class="breadcrumb">
            @foreach ($links as $title => $url)
                <li>
                    @if ($url)
                        {!! link_to($url,  $title) !!}
                    @else
                        {!! $title !!}
                    @endif
                </li>
            @endforeach
        </ol>
    @endif
</div>