<div class="widget widget-languages">
    @foreach($countries as $country)
        @if (true /* Session::get('app.locale') === $languageCode */)

        @else

        @endif
        {!! HTML::imageLink(asset($country->uploadPath().$country->icon), $country->code, '#') !!}
    @endforeach
</div>