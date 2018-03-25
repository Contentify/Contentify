@extends('pure_message')

@section('content')
    <div class="message-box">
        <h1>Error</h1>

        <hr>

        <p>{!! $exception->getMessage() !!}</p>

        <hr>
        
        <button onclick="window.location='{!! route('home') !!}'">Website</button>
    </div>
@stop