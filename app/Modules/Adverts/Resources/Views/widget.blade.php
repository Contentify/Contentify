<div id="{!! $containerId !!}">
    @if ($advert->code)
        {!! $advert->code !!}
    @else
        @if ($advert->image)
            <a href="{!! url('adverts/url/'.$advert->id) !!}" title="{{ $advert->title }}" target="_blank">
                <img src="{!! $advert->uploadPath().$advert->image !!}" alt="{{ $advert->title }}">
            </a>
        @endif
    @endif
</div>