<div class="widget widget-downloads">
    <ul class="list-unstyled">
        @foreach ($downloads as $download)
            <li>
                {!! link_to('downloads/'.$download->id.'/'.$download->slug,  $download->title) !!}
            </li>
        @endforeach
    </ul>
</div>