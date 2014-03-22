<div class="breadcrumb-navi">
    @foreach ($links as $title => $url)
        {{ link_to($url,  $title) }}
    @endforeach
</div>