<h1 class="page-title">Galleries</h1>

<div class="galleries">
    @foreach ($galleries as $gallery)
        <h2>{{{ $gallery->title }}}</h2>
        
        <div class="gallery clearfix">
            @foreach ($gallery->images->take(5) as $image)
                <a class="item" href="{{{ url('galleries/'.$gallery->id.'/'.$image->id.$image->gallerySlug()) }}}" title="{{{ $image->title }}}">
                    <div class="image" style="background-image: url('{{ $image->uploadPath().'200/'.$image->image }}')"></div>
                </a>
            @endforeach
        </div>
    @endforeach
</div>

{{ $galleries->render() }}