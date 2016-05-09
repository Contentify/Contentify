<div class="widget widget-random-user">
    <a href="{{ 'users/'.$user->id.'/'.$user->slug }}">
        <div class="image">
            @if ($user->image)
                <img src="{!! $user->uploadPath().$user->image !!}" alt="{{ $user->username }}">
            @else
                <img src="{!! asset('theme/user.png') !!}" alt="{{ $user->username }}">
            @endif
        </div>
        {{ $user->username }}
    </a>
</div>