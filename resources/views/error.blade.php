@extends('pure_message')

@section('content')
    <div class="message-box">
        <h1>Error</h1>

        <hr>

        <p>Sorry, something bad happened. Our website has crashed.</p>
        <p>Don't worry, we will handle this. In the meantime, make a cup of coffee. And stop crushing our website!</p>

        <hr>
        
        <button onclick="window.location='{!! route('home') !!}'">Website</button>
    </div>
@stop