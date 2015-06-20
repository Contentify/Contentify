<div class="widget widget-galleries">
    <ul class="list-inline">
        @foreach ($images as $image)
            <li>
                <a class="item" href="{{ url('galleries/'.$image->gallery->id.'/'.$image->id.$image->gallerySlug()) }}#anchor-images" title="{{ $image->title }}">
                    <div class="image" style="background-image: url('{!! $image->uploadPath().'200/'.$image->image !!}')"></div>
                </a>
            </li>
        @endforeach
    </ul>
</div>