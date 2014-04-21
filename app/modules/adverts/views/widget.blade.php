@if ($advert->code)
<div>
    {{ $advert->code }}
</div>
@else
@if ($advert->image)
<a href="{{ url('adverts/'.$advert->id) }}" title="{{{ $advert->title }}}" target="_blank">
    <img src="{{ $advert->uploadPath().$advert->image }}" alt="{{{ $advert->title }}}">
</a>
@endif
@endif