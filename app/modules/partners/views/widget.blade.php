<ul class="layout-v">
@foreach ($partners as $partner)
@if ($partner->image)
<li>
    <a href="{{ url('partners/'.$partner->id) }}" title="{{ $partner->title }}" target="_blank">
        <img src="{{ $partner->uploadPath().$partner->image }}" alt="{{ $partner->title }}" style="max-width: 100%">
    </a>
</li>
@endif
@endforeach
</ul>