<h1 class="page-title"><a href="{{ url('downloads/category/'.$download->downloadcat->id.'/'.$download->downloadcat->slug) }}">Downloads - {{ $download->downloadcat->title }}</a></h1>

<div class="download-detail">
    {{ Form::open(array('url' => 'downloads/perform/'.$download->id)) }}
        {{ Form::smartGroupOpen(null, trans('app.file')) }}
            {{{ $download->title }}}
        {{ Form::smartGroupClose() }}

        {{ Form::smartGroupOpen(null, trans('app.category')) }}
            {{ $download->downloadcat->title }}
        {{ Form::smartGroupClose() }}

        {{ Form::smartGroupOpen(null, trans('app.size')) }}
            {{ $download->file_size }} Bytes
        {{ Form::smartGroupClose() }}

        {{ Form::smartGroupOpen(null, trans('downloads::download_counter')) }}
            {{ $download->access_counter }}
        {{ Form::smartGroupClose() }}

        {{ Form::smartGroupOpen(null, trans('downloads::traffic')) }}
            {{ $download->file_size * $download->access_counter }} Bytes
        {{ Form::smartGroupClose() }}

        @if ($download->description)
        <p>
            {{ $download->description }}
        </p>
        @endif

        {{ Form::actions(['submit' => trans('downloads::perform_download')]) }}
    {{ Form::close() }}
</div>

{{ Comments::show('downloads', $download->id) }}