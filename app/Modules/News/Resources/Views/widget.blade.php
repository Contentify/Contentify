<div class="widget widget-news">
    <ul class="list-unstyled">
        @foreach ($newsCollection as $news)
            <li>
                <a href="{{ url('news/'.$news->id.'/'.$news->slug) }}">{{ $news->title }}</a>
            </li>
        @endforeach
    </ul>
</div>