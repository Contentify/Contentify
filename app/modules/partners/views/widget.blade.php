<ul class="layout-v">
@foreach ($partners as $partner)
<li>
    {{ link_to('news/'.$news->id,  $news->title) }}
</li>
@endforeach
</ul>