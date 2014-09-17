<ul class="layout-v widget-partners">
@foreach ($slides as $slide)
@if ($slide->image)
<li>
    <a href="{{ $slide->url }}" title="{{{ $slide->title }}}" target="_blank">
        <img src="{{ $slide->uploadPath().$slide->image }}" alt="{{{ $slide->title }}}">
    </a>
</li>
@endif
@endforeach
</ul>