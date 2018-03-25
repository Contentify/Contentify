@extends('pure_message')

@section('content')
    <div class="message-box">
        <h1>Resource Not Found</h1>

        <hr>

        <p>Sorry, something bad happened.</p>
        <p>The resource you're looking for is not available.</p>
        <p>Maybe you want to {!! link_to('search', 'search') !!} for it?</p>

        <hr>

        <button onclick="window.location='{!! route('home') !!}'">Website</button>
    </div>
@stop