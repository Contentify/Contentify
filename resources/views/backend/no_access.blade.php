@extends('pure_message')

@section('content')
    <div class="message-box">
        <h1>Access Denied</h1>

        <hr>

        <p>Warning: Access denied.</p>
        <p>You are not allowed to access the backend!</p>

        <hr>
        
        <button onclick="window.location='{!! route('home') !!}'">Cancel</button>
    </div>
@stop