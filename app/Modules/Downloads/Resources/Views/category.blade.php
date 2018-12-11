<h1 class="page-title"><a class="back" href="{!! url('downloads') !!}" title="{{ trans('app.back') }}">{!! HTML::fontIcon('chevron-left') !!}</a> {{ trans_object('downloads') }} - {{ $downloadCat->title }}</h1>

<div class="downloads clearfix">
    @forelse ($downloads as $download)
        <div class="download">
            @if ($download->is_image)
                <a href="{{ url('downloads/'.$download->id.'/'.$download->slug) }}" style="background-image: url('{!! $download->uploadPath().'50/'.$download->file !!}')">
            @else
                <a href="{{ url('downloads/'.$download->id.'/'.$download->slug) }}">
            @endif
                    {!! $download->title !!}
                </a>
        </div>
    @empty
        <p>{{ trans('app.nothing_here') }}</p>
    @endforelse
</div>

<div class="clear"></div>
{!! $downloads->render() !!}
