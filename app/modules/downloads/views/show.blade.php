<h1 class="page-title"><a href="{{ url('downloads/category/'.$download->downloadcat->id.'/'.$download->downloadcat->slug) }}">Downloads - {{ $download->downloadcat->title }}</a></h1>

<div class="download-detail">
    {{ Form::open(array('url' => 'downloads/perform/'.$download->id)) }}
        {{ Form::smartFieldOpen(trans('app.file')) }}
            {{{ $download->title }}}
        {{ Form::smartFieldClose() }}

        {{ Form::smartFieldOpen(trans('app.category')) }}
            {{ $download->downloadcat->title }}
        {{ Form::smartFieldClose() }}

        {{ Form::smartFieldOpen(trans('app.size')) }}
            {{ $download->file_size }} Bytes
        {{ Form::smartFieldClose() }}

        {{ Form::smartFieldOpen(trans('downloads::download_counter')) }}
            {{ $download->access_counter }}
        {{ Form::smartFieldClose() }}

        {{ Form::smartFieldOpen(trans('downloads::traffic')) }}
            {{ $download->file_size * $download->access_counter }} Bytes
        {{ Form::smartFieldClose() }}

        @if ($download->description)
        <p>
            {{ $download->description }}
        </p>
        @endif

        {{ Form::actions(['submit' => trans('downloads::perform_download')]) }}
    {{ Form::close() }}
</div>

{{ Comments::show('downloads', $download->id) }}