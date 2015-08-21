<div id="comments" data-foreign-type="{!! $foreignType !!}" data-foreign-id="{!! $foreignId !!}">
    @foreach ($comments as $comment)
        @include('comments.comment')
    @endforeach
    
    {!! $comments->render() !!}
</div>

@if (user())
	{!! HTML::script('vendor/contentify/comments.js') !!}
@else
	<div class="comments-login-info well">{!! trans('comments::login_info', [link_to('auth/login', lcfirst(trans('auth::login'))), link_to('auth/registration/create', lcfirst(trans('auth::register')))]) !!}</div>
@endif