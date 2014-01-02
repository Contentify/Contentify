@extends('backend.pure_message')

@section('content')
<div class="message-box">
	<h1>Access denied</h1>
	<hr>
	<p>Warning: Access denied.</p>
	<p>You are not allowed to access the backend!</p>
	<hr>
	<button onclick="javascript:window.location='{{ route('home') }}'">Back</button>
</div>
@stop