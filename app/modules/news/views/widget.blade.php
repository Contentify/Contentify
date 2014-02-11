<ul class="layout-v">
@foreach ($newsCollection as $news)
<li>
    {{ link_to('news/'.$news->id,  $news->title) }}
</li>
@endforeach
</ul>