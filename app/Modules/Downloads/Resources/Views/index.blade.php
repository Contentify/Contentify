<h1 class="page-title">{{ trans_object('downloads') }}</h1>

<div class="downloadcats clearfix">
    @foreach ($downloadcats as $downloadcat)
        <div class="downloadcat">
            <a href="{{ url('downloads/category/'.$downloadcat->id.'/'.$downloadcat->slug) }}">
                <img src="{!! asset('theme/folder.png') !!}">
                {{ $downloadcat->title }}
            </a>
        </div>
    @endforeach
</div>

{!! $downloadcats->render() !!}