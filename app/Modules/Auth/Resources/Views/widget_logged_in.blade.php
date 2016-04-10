<div class="widget widget-auth-logged-in">
    <ul class="list-inline">
        <li>{!! link_to('users/'.user()->id.'/'.user()->slug, trans('app.profile')) !!}</li>
        <li>{!! link_to('users/'.user()->id.'/edit', trans('app.edit_profile')) !!}</li>
        <li>
            @if (user()->countMessages() > 0)
                {!! link_to('messages/inbox', user()->countMessages().' '.trans_object('messages')) !!}
            @else
                {!! link_to('messages/inbox', trans_object('messages')) !!}
            @endif
        </li>
        <li>{!! link_to('friends/'.user()->id, trans_object('friends')) !!}</li>
        @if (user()->hasAccess('backend', PERM_READ))
            <li>{!! link_to('admin', trans('auth::backend')) !!}</li>
        @endif
        <li>{!! link_to('auth/logout', trans('app.logout')) !!}</li>
    </ul>
</div>