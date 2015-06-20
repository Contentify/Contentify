<div class="alert alert-{!! $type !!} alert-static">
    <div class="title">{!! $title !!}</div>

    @if (isset($text))
        <div class="text">{!! $text !!}</div>
    @endif
</div>