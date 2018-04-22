<h1 class="page-title">{{ trans_object('downloads') }}</h1>

<div class="download-cats clearfix">
    @foreach ($downloadCats as $downloadCat)
        <div class="download-cat">
            <a href="{{ url('downloads/category/'.$downloadCat->id.'/'.$downloadCat->slug) }}">
                <img src="{!! asset('img/default/folder.png') !!}">
                {{ $downloadCat->title }}
            </a>
        </div>
    @endforeach
</div>

{!! $downloadCats->render() !!}