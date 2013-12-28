<h1 class="page-title">Search</h1>

{{ Form::errors($errors) }}

{{ Form::open(array('url' => 'search/create')) }}
    <input name="_createdat" type="hidden" value="{{ time() }}">

    {{ Form::smartText('subject', 'Subject') }}

    {{ Form::actions(['submit' => 'Search']) }}
{{ Form::close() }}

@foreach ($resultBags as $resultBag)
<h2>Results of type {{ $resultBag['title'] }}:</h2>
<ul>
@foreach ($resultBag['results'] as $result)
    <li>{{ $result->title }}</li>
@endforeach
</ul>
@endforeach