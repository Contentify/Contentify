<div id="slider{{ $categoryId }}" class="slider">
    <div class="slides">
        <ul class="layout-h">
        	@foreach ($slides as $slide)
            <li>
            	<a href="{{ $slide->url }}" title="{{{ $slide->title }}}" target="_blank">
			        <img src="{{ $slide->uploadPath().$slide->image }}" alt="{{{ $slide->title }}}">
			    </a>
			</li>
			@endforeach
        </ul>
    </div>
    <a class="to-left" href="#">&lt;</a>
    <a class="to-right" href="#">&gt;</a>
    <ul class="buttons layout-h" title="Switch to">
        @foreach ($slides as $index => $slide)
        	<li>
        		{{ $index + 1 }}
        	</li>
        @endforeach
    </ul>
</div>

{{ HTML::script('libs/slider.js') }}
<script>
	$(document).ready(function()
	{
		$('#slider{{ $categoryId }}').contentifySlider();
	});
</script>