<h1 class="page-title">{{ trans_object('galleries') }}</h1>

<div class="galleries">
    @forelse ($galleries as $gallery)
        <h2>{{ $gallery->title }}</h2>
        
        <div class="gallery clearfix">
            @foreach ($gallery->images->take(5) as $image)
                <a class="item" href="{{ url('galleries/'.$gallery->id.'/'.$image->id.$image->gallerySlug()) }}" title="{{ $image->title }}">
                    <div class="image" style="background-image: url('{!! $image->uploadPath().'200/'.$image->image !!}')"></div>
                </a>
            @endforeach
        </div>
    @empty
        <p>{{ trans('app.nothing_here') }}</p>
    @endforelse
</div>

{!! $galleries->render() !!}
