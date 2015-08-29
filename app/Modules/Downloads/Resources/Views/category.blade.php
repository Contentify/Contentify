<h1 class="page-title">{{ trans_object('downloads') }} - {{ $downloadcat->title }}</h1>

<div class="downloads clearfix">
    @foreach ($downloads as $download)
        <div class="download">
            @if ($download->is_image)
                <a href="{{ url('downloads/'.$download->id.'/'.$download->slug) }}" style="background-image: url('{!! $download->uploadPath().'50/'.$download->file !!}')">
            @else
                <a href="{{ url('downloads/'.$download->id.'/'.$download->slug) }}">
            @endif
                    {!! $download->title !!}
                </a>
        </div>
    @endforeach
</div>

<div class="clear"></div>
{!! $downloads->render() !!}