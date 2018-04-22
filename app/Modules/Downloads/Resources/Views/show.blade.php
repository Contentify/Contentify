<h1 class="page-title"><a class="back" href="{!! url('downloads/category/'.$download->downloadCat->id.'/'.$download->downloadCat->slug) !!}" title="{{ trans('app.back') }}">{!! HTML::fontIcon('chevron-left') !!}</a> {{ trans_object('downloads') }} - {{ $download->downloadCat->title }}</h1>

<div class="download-detail">
    {!! Form::open(array('url' => 'downloads/perform/'.$download->id)) !!}
        {!! Form::smartGroupOpen(null, trans('app.file')) !!}
            <p class="form-control-static">{{ $download->title }}</p>
        {!! Form::smartGroupClose() !!}

        @if ($download->is_image)
            {!! Form::smartGroupOpen(null, trans('app.preview')) !!}
                <img src="{!! $download->uploadPath().'50/'.$download->file !!}" alt="{{ trans('app.preview') }}">
            {!! Form::smartGroupClose() !!}
        @endif

        {!! Form::smartGroupOpen(null, trans('app.category')) !!}
            <p class="form-control-static">{{ $download->downloadCat->title }}</p>
        {!! Form::smartGroupClose() !!}

        {!! Form::smartGroupOpen(null, trans('app.size')) !!}
            <p class="form-control-static">{{ $download->file_size }} Bytes</p>
        {!! Form::smartGroupClose() !!}

        {!! Form::smartGroupOpen(null, trans('downloads::download_counter')) !!}
            <p class="form-control-static">{{ $download->access_counter }}</p>
        {!! Form::smartGroupClose() !!}

        {!! Form::smartGroupOpen(null, trans('downloads::traffic')) !!}
            <p class="form-control-static">{{ $download->file_size * $download->access_counter }} Bytes</p>
        {!! Form::smartGroupClose() !!}

        @if ($download->description)
            <p>
                {!! $download->description !!}
            </p>
        @endif

        {!! Form::actions(['submit' => trans('downloads::perform_download')]) !!}
    {!! Form::close() !!}
</div>

{!! Comments::show('downloads', $download->id) !!}