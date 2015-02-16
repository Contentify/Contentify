<h1 class="page-title">{{ $user->username }}</h1>

<div class="profile-basics">
    <ul class="list-unstyled">
        <li>
            <span class="title">{{ trans('app.name') }}:</span>
            @if ($user->country->icon)
                {{ HTML::image($user->country->uploadPath().$user->country->icon, $user->country->title) }}
            @endif
             {{{ $user->first_name }}} {{{ $user->last_name }}}
        </li>
        <li>
            <span class="title">{{ trans('users::gender') }}:</span>
            {{{ $user->gender }}}
        </li>
        <li>
            <span class="title">{{ trans('users::language') }}:</span>
            {{{ $user->language->title }}}
        </li>
        <li>
            <span class="title">{{ trans('users::birthdate') }}:</span>
            {{{ $user->birthdate }}}
        </li>
        <li>
            <span class="title">{{ trans('users::occupation') }}:</span>
            {{{ $user->occupation }}}
        </li>
        <li>
            <span class="title">{{ trans('users::website') }}:</span>
            @if(e($user->website))
                {{ HTML::link(e($user->website)) }}
            @endif
        </li>
        <li>
            <span class="title">{{ trans('users::about') }}:</span>
            {{{ $user->about }}}
        </li>
    </ul>
</div>

<div class="profile-socials">
    <ul class="list-unstyled">
        <li>
            <span class="title">Facebook:</span>
            {{{ $user->facebook }}}
        </li>
        <li>
            <span class="title">Twitter:</span>
            {{{ $user->twitter }}}
        </li>
        <li>
            <span class="title">Skype:</span>
            {{{ $user->skype }}}
        </li>
        <li>
            <span class="title">{{ trans('users::steam_id') }}:</span>
            <a href="http://steamcommunity.com/id/{{{ $user->steam_id }}}">{{{ $user->steam_id }}}</a>
        </li>
    </ul>
</div>

<div class="profile-pc">
    <ul class="list-unstyled">
        <li>
            <span class="title">{{ trans('users::cpu') }}:</span>
            {{{ $user->cpu }}}
        </li>
        <li>
            <span class="title">{{ trans('users::graphics') }}:</span>
            {{{ $user->graphics }}}
        </li>
        <li>
            <span class="title">{{ trans('users::ram') }}:</span>
            {{{ $user->ram }}}
        </li>
        <li>
            <span class="title">{{ trans('users::motherboard') }}:</span>
            {{{ $user->motherboard }}}
        </li>
        <li>
            <span class="title">{{ trans('users::drives') }}:</span>
            {{{ $user->drives }}}
        </li>
        <li>
            <span class="title">{{ trans('users::display') }}:</span>
            {{{ $user->display }}}
        </li>
        <li>
            <span class="title">{{ trans('users::mouse') }}:</span>
            {{{ $user->mouse }}}
        </li>
        <li>
            <span class="title">{{ trans('users::keyboard') }}:</span>
            {{{ $user->keyboard }}}
        </li>
        <li>
            <span class="title">{{ trans('users::headset') }}:</span>
            {{{ $user->headset }}}
        </li>
        <li>
            <span class="title">{{ trans('users::mousepad') }}:</span>
            {{{ $user->mousepad }}}
        </li>
    </ul>
</div>

<div class="profile-favs">
    <ul class="list-unstyled">
        <li>
            <span class="title">{{ trans('users::game') }}:</span>
            {{{ $user->game }}}
        </li>
        <li>
            <span class="title">{{ trans('users::food') }}:</span>
            {{{ $user->food }}}
        </li>
        <li>
            <span class="title">{{ trans('users::drink') }}:</span>
            {{{ $user->drink }}}
        </li>
        <li>
            <span class="title">{{ trans('users::music') }}:</span>
            {{{ $user->music }}}
        </li>
        <li>
            <span class="title">{{ trans('users::film') }}:</span>
            {{{ $user->film }}}
        </li>
    </ul>
</div>