<div class="widget widget-global-search">
    {!! Form::open(array('url' => 'search/create')) !!}
        <input name="_createdat" type="hidden" value="{!! time() !!}">

        {!! Form::smartText('subject', trans('app.subject')) !!}

        {!! Form::actions(['submit' => trans('app.search')], false) !!}
    {!! Form::close() !!}
</div>