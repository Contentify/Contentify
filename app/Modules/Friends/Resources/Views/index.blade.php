<h1 class="page-title">{{ trans_object('friends') }} {{ trans('app.of') }} {{ $user->username }}</h1>

@foreach ($user->friends() as $friend)
    <div class="friend">
        <a href="{!! url('users/'.$friend->id.'/'.$friend->slug) !!}">
            <div class="img">
                @if ($friend->image)
                    <img src="{!! $friend->uploadPath().$friend->image !!}" alt="{{ $friend->username }}">
                @else
                    <img src="{!! asset('img/default/no_user.png') !!}" alt="{{ $friend->username }}">
                @endif
            </div>
            <div class="name">
                @if ($friend->isOnline())
                    <span title="{{ trans('app.online') }}">{!! HTML::fontIcon('clock') !!}</span>
                @endif
                {{ $friend->username }}
            </div>
        </a>

        @if (user() and $user->id == user()->id)
            <div class="actions">
                <a class="btn btn-default" href="{!! url('friends/'.$friend->id) !!}?method=DELETE&amp;_token={!! csrf_token() !!}" title="{{ trans('app.remove') }}">{!! HTML::fontIcon('trash') !!}</a>
            </div>
        @endif
    </div>
@endforeach