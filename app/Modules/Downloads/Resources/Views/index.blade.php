<h1 class="page-title">{{ trans_object('downloads') }}</h1>

<div class="download-cats clearfix">
    @forelse ($downloadCats as $downloadCat)
        <div class="download-cat">
            <a href="{{ url('downloads/category/'.$downloadCat->id.'/'.$downloadCat->slug) }}">
                <img src="{!! asset('img/default/folder.png') !!}">
                {{ $downloadCat->title }}
            </a>
        </div>
    @empty
        <p>{{ trans('app.nothing_here') }}</p>
    @endforelse
</div>

{!! $downloadCats->render() !!}
