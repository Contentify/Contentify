<div class="toolbar top">
    {!! button(trans('app.create'), route('admin.images.create'), 'plus-circle') !!}

    <div class="model-search-box">
        {!! Form::open(['url' => URL::current().'/search']) !!}
                {!! Form::text('search', $searchString, ['class' => 'search-string']) !!}
                {!! Form::submit(trans('app.search'), ['class' => 'btn btn-default']) !!}
        {!! Form::close() !!}
    </div>
</div>

<div class="clearfix">
    @forelse ($images as $image)
        <div class="image" style="background-image: url('{{ $image->uploadPath().'200/'.$image->image }}')">
            <a class="show" href="{{ $image->uploadPath().$image->image }}" target="_blank"></a>
            <div class="info">
                <span title="Tags">{{ $image->tags }}</span>
                @if ($image->gallery)
                    <div class="gallery">{!! link_to('galleries/'.$image->gallery->id, 'Gallery: '.e($image->gallery->title)) !!}</div>
                @endif
            </div>
            <div class="actions">
                {!! icon_link('edit', trans('app.edit'), route('admin.images.edit', [$image->id])) !!}
                {!! icon_link('trash', trans('app.delete'), route('admin.images.destroy', [$image->id]).'?method=DELETE&_token='.csrf_token(), false, ['data-confirm-delete' => true]) !!}
            </div>
        </div>
    @empty
        <p>{{ trans('search::no_results') }}</p>
    @endforelse
</div>

{!! $images->render() !!}
