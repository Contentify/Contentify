<ul class="layout-v">
	@foreach ($downloads as $download)
	<li>
	    {{ link_to('downloads/'.$download->id.'/'.$download->slug,  $download->title) }}
	</li>
	@endforeach
</ul>