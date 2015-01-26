<div class="widget widget-auth-logged-in">
    <ul class="list-unstyled">
        <li>{{ link_to('users/'.user()->id.'/'.user()->slug, trans('auth::profile')) }}</li>
        <li>{{ link_to('users/'.user()->id.'/edit', trans('auth::edit_profile')) }}</li>
        <li>
            @if (user()->countMessages() > 0)
                {{ link_to('messages/inbox', user()->countMessages().' Messages') }}
            @else
                {{ link_to('messages/inbox', 'Messages') }}
            @endif            
        </li>
        @if (user()->hasAccess('backend', PERM_READ))
            <li>{{ link_to('admin', trans('auth::backend')) }}</li>
        @endif
        <li>{{ link_to('auth/logout', trans('auth::logout')) }}</li>
    </ul>
</div>