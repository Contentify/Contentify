<div class="widget widget-news">
<ul class="list-unstyled">
    @foreach ($newsCollection as $news)
        <li>
            {{ link_to('news/'.$news->id.'/'.$news->slug,  $news->title) }}
        </li>
    @endforeach
</ul>