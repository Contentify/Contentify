<h1 id="anchor-images" class="page-title">{{ $gallery->title }}</h1>

<div class="images">
    <div class="image">
        <a href="{!! $image->uploadPath().$image->image !!}" target="_blank" title="{{ $image->title }}">
            <img src="{!! $image->uploadPath().$image->image !!}">
        </a>
        <p>
            {{ $image->title }}
        </p>
    </div>

    <div class="previews-wrapper">
        <div class="previews">
            @foreach ($gallery->images as $prevImage)
                <a class="item" href="{{ url('galleries/'.$gallery->id.'/'.$prevImage->id.$prevImage->gallerySlug()) }}#anchor-images" title="{{ $prevImage->title }}" data-id="{!! $prevImage->id !!}">
                    <div class="image" style="background-image: url('{!! $prevImage->uploadPath().'200/'.$prevImage->image !!}')"></div>
                </a>
            @endforeach
        </div>
        
        <a class="link to-left" href="#">&lt;</a>
        <a class="link to-right" href="#">&gt;</a>
    </div>
</div>

<script>
    $(document).ready(function()
    {
        var $container  = $('.page .images .previews-wrapper');
        var $previews   = $container.find('.previews');
        var $curImg     = $previews.find('.item[data-id={!! $image->id !!}]');
        var index       = $curImg.index();
        var itemLength  = $previews.find('.item').length;

        $curImg.addClass('active');

        // Image previews: Scroll to the current image
        $previews.css({
            marginLeft: $container.width() / 2 - index * $curImg.width() - $curImg.width() / 2,
            width: $curImg.width() * itemLength
        });

        // Image previews: Scroll to the left
        $('.page .images .to-left').click(function(event) 
        {
            event.preventDefault();

            index--;
            if (index < 0) {
                index = 0;
            }

            $previews.animate(
                {marginLeft: $container.width() / 2 - index * $curImg.width() - $curImg.width() / 2}, 
                {duration: 200, queue: false}
            );
        });

        // Image previews: Scroll to the right
        $('.page .images .to-right').click(function(event) 
        {
            event.preventDefault();

            index++;
            if (index >= itemLength) {
                index = itemLength - 1;
            }

            $previews.animate(
                {marginLeft: $container.width() / 2 - index * $curImg.width() - $curImg.width() / 2}, 
                {duration: 200, queue: false}
            );
        });
    });
</script>

{!! Comments::show('images', $image->id) !!}