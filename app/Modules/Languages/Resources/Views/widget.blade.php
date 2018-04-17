<div class="widget widget-languages">
    @foreach($countries as $country)
        <?php $attributes = (Session::get('app.locale') === $country->code) ? ['data-active' => 1] : [] ?>
        {!! HTML::imageLink(asset($country->uploadPath().$country->icon), strtoupper($country->code), url('languages/'.$country->code), false, $attributes) !!}
    @endforeach
</div>