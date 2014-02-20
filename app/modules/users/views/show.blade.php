<h1 class="page-title">{{ $user->username }}</h1>

<div class="profile-basics">
    <ul class="layout-v">
        <li>
            <span class="title">Name:</span>
            {{ HTML::image(asset('icons/flags/'.$user->country.'.png', $user->country)) }} {{{ $user->first_name }}} {{{ $user->last_name }}}
        </li>
        <li>
            <span class="title">Gender:</span>
            {{{ $user->gender }}}
        </li>
        <li>
            <span class="title">Birthday:</span>
            {{{ $user->birthdate }}}
        </li>
        <li>
            <span class="title">Occupation:</span>
            {{{ $user->occupation }}}
        </li>
        <li>
            <span class="title">Website:</span>
            @if(e($user->website))
            {{ HTML::link(e($user->website)) }}
            @endif
        </li>
        <li>
            <span class="title">About:</span>
            {{{ $user->about }}}
        </li>
    </ul>
</div>

<div class="profile-socials">
    <ul class="layout-v">
        <li>
            <span class="title">Skype:</span>
            {{{ $user->skype }}}
        </li>
        <li>
            <span class="title">Steam:</span>
            <a href="http://steamcommunity.com/id/{{{ $user->steam_id }}}">{{{ $user->steam_id }}}</a>
        </li>
    </ul>
</div>

<div class="profile-pc">
    <ul class="layout-v">
        <li>
            <span class="title">CPU:</span>
            {{{ $user->cpu }}}
        </li>
        <li>
            <span class="title">Graphics:</span>
            {{{ $user->graphics }}}
        </li>
        <li>
            <span class="title">RAM:</span>
            {{{ $user->ram }}}
        </li>
        <li>
            <span class="title">Motherboard:</span>
            {{{ $user->motherboard }}}
        </li>
        <li>
            <span class="title">Drives:</span>
            {{{ $user->drives }}}
        </li>
        <li>
            <span class="title">Display:</span>
            {{{ $user->display }}}
        </li>
        <li>
            <span class="title">Mouse:</span>
            {{{ $user->mouse }}}
        </li>
        <li>
            <span class="title">Keyboard:</span>
            {{{ $user->keyboard }}}
        </li>
        <li>
            <span class="title">Headset:</span>
            {{{ $user->headset }}}
        </li>
        <li>
            <span class="title">Mousepad:</span>
            {{{ $user->mousepad }}}
        </li>
    </ul>
</div>

<div class="profile-favs">
    <ul class="layout-v">
        <li>
            <span class="title">Game:</span>
            {{{ $user->game }}}
        </li>
        <li>
            <span class="title">Food:</span>
            {{{ $user->food }}}
        </li>
        <li>
            <span class="title">Drink:</span>
            {{{ $user->drink }}}
        </li>
        <li>
            <span class="title">Music:</span>
            {{{ $user->music }}}
        </li>
        <li>
            <span class="title">Film:</span>
            {{{ $user->film }}}
        </li>
    </ul>
</div>