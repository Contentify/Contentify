<div class="widget widget-random-user">
    <a href="{{ 'users/'.$user->id.'/'.$user->slug }}">
        <div class="image">
            @if ($user->image)
                <img src="{!! $user->uploadPath().$user->image !!}" alt="{{ $user->username }}">
            @else
                <img src="{!! asset('img/default/no_user.png') !!}" alt="{{ $user->username }}">
            @endif
        </div>
        <div>
            @if ($user->country->icon)
                {!! HTML::image($user->country->uploadPath().$user->country->icon, $user->country->title) !!}
            @endif
            {{ $user->username }}
        </div>
    </a>
</div>