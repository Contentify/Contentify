<h1 class="page-title">Downloads - {{{ $downloadcat->title }}}</h1>

<div class="downloads">
    @foreach ($downloads as $download)
        <div class="download">
            @if ($download->is_image)
                <a href="{{{ url('downloads/'.$download->id.'/'.$download->slug) }}}" style="background-image: url('{{ $download->uploadPath().'50/'.$download->file }}')">
            @else
                <a href="{{{ url('downloads/'.$download->id.'/'.$download->slug) }}}">
            @endif
                    {{ $download->title }}
                </a>
        </div>
    @endforeach
</div>

<div class="clear"></div>
{{ $downloads->links() }}