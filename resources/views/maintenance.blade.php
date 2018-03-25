@extends('pure_message')

@section('content')
    <div class="message-box">
        <h1>Maintenance Mode</h1>

        <hr>

        <p>This website is in maintenance mode.</p>
        <p>We'll be right back!</p>

        <hr>
        
        <button onclick="window.location='{!! route('home') !!}'">Reload</button>
    </div>
@stop