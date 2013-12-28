<h1 class="page-title">Search</h1>

{{ Form::errors($errors) }}

{{ Form::open(array('url' => 'search/create')) }}
    <input name="_createdat" type="hidden" value="{{ time() }}">

    {{ Form::smartText('subject', 'Subject') }}

    {{ Form::actions(['submit' => 'Search']) }}
{{ Form::close() }}

@if (isset($resultBags))
@foreach ($resultBags as $resultBag)
<h2>Results of type {{ $resultBag['title'] }}:</h2>
<ul>
    @foreach ($resultBag['results'] as $title => $url)
    <li>{{ HTML::link($url, $title) }}</li>
    @endforeach
</ul>
@endforeach
@endif