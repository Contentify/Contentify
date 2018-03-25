@extends('pure_message')

@section('content')
    <div class="message-box">
        <h1>Login</h1>

        <hr>

        <p>You are not logged in.</p>
        <p>Please log in to get access to the backend!</p>
        
        <hr>

        <button onclick="window.location='{!! route('login') !!}'">Login</button>
    </div>
@stop