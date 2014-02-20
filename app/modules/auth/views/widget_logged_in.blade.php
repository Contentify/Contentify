<ul class="layout-v">
    <li>{{ link_to('users/'.user()->id.'/'.slug(user()->username), 'Profile') }}</li>
    @if (user()->hasAccess('backend', PERM_READ))
    <li>{{ link_to('admin', 'Admin-Backend') }}</li>
    @endif
    <li>{{ link_to('auth/logout', 'Logout') }}</li>
</ul>