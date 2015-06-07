<h1 class="page-title">{{ trans('search::search') }}</h1>

{{ Form::errors($errors) }}

{{ Form::open(array('url' => 'search/create')) }}
    <input name="_createdat" type="hidden" value="{{ time() }}">

    {{ Form::smartText('subject', trans('search::subject')) }}

    {{ Form::actions(['submit' => trans('search::search')], false) }}
{{ Form::close() }}

@if (isset($resultBags))
    @foreach ($resultBags as $resultBag)
        <h2>{{ trans('search::results_type') }} {{ $resultBag['title'] }}:</h2>
        
        <ul>
            @foreach ($resultBag['results'] as $title => $url)
                <li>{{ HTML::link($url, $title) }}</li>
            @endforeach
        </ul>
    @endforeach
@endif