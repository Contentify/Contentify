<h2>{{ $msg->title }}</h2>

<hr>

<blockquote>{!! $msg->text !!}</blockquote> 

<hr>

<p><strong>{{ trans('app.creator') }}:</strong> {{ $msg->username }} ({{ trans('app.email') }}: {{ $msg->email }}, {{ trans('app.ip') }}: {{ $msg->ip }}) - <strong>{{ trans('app.created_at') }}:</strong>  {!! $msg->created_at->dateTime() !!}</p>

<hr>

{!! Form::button(trans('app.reply'), ['onclick' => "location.href='mailto:{$msg->email}'"]) !!}