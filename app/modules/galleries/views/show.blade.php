<h1 id="anchor-images" class="page-title">{{{ $gallery->title }}}</h1>

<div class="images">
	<div class="image">
		<a href="{{ $image->uploadPath().$image->image }}" target="_blank" title="{{{ $image->title }}}">
	    	<img src="{{ $image->uploadPath().$image->image }}" />
	    	@if ($image->title)
	    	@endif
	    </a>
		<p>
			{{{ $image->title }}}
		</p>
	</div>
	<div class="previews-wrapper">
		<div class="previews">
	        @foreach ($gallery->images as $prevImage)
	            <a class="item" href="{{{ url('galleries/'.$gallery->id.'/'.$prevImage->id.$prevImage->gallerySlug()) }}}#anchor-images" title="{{{ $prevImage->title }}}" data-id="{{ $prevImage->id }}">
	                <div class="image" style="background-image: url('{{ $prevImage->uploadPath().'200/'.$prevImage->image }}')"></div>
	            </a>
	        @endforeach
	    </div>
	</div>
</div>

<script>
	$(document).ready(function()
	{
		var $container 	= $('.page .images .previews-wrapper');
		var $previews 	= $container.find('.previews');
		var $curImg 	= $previews.find('.item[data-id={{ $image->id }}]');
		var index 		= $curImg.index();
		$curImg.addClass('active');
		$previews.css({
			marginLeft: $container.width() / 2 - index * $curImg.width() - $curImg.width() / 2,
			width: $curImg.width() * $previews.find('.item').length
		});
	});
</script>

{{ Comments::show('images', $image->id) }}