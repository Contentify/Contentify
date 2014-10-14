@if (user()->hasAccess('users'))
<h2 class="section">Visitors</h2>
@widget('Visitors::Chart')
@endif

<h2 class="section">{{ trans('dashboard::quick_access') }}</h2>
<div class="quick-access">     
    <div>
        <a href="{{ url('admin/news') }}"><img src="{{ asset('icons/48_news.png') }}" width="48" height="48" alt="Icon" />News</a>
    </div>
    <div>
        <a href="{{ url('admin/diag') }}"><img src="{{ asset('icons/48_headlines.png') }}" width="48" height="48" alt="Icon" />Diag</a>
    </div>
    <div>
        <a href="{{ url('admin/pages') }}"><img src="{{ asset('icons/48_pages.png') }}" width="48" height="48" alt="Icon" />Eigene Seiten</a>
    </div>
    <div>
        <a href="{{ url('admin/matches') }}"><img src="{{ asset('icons/48_matches.png') }}" width="48" height="48" alt="Icon" />Matches</a>
    </div>
    <div>
        <a href="{{ url('admin/images') }}"><img src="{{ asset('icons/48_images.png') }}" width="48" height="48" alt="Icon" />Images</a>
    </div>
    <div>
        <a href="{{ url('admin/videos') }}"><img src="{{ asset('icons/48_videos.png') }}" width="48" height="48" alt="Icon" />Videos</a>
    </div>
    
    <div>
        <a href="{{ url('admin/users') }}"><img src="{{ asset('icons/48_users.png') }}" width="48" height="48" alt="Icon" />Users</a>
    </div>
    <div>
        <a href="{{ url('admin/members') }}"><img src="{{ asset('icons/48_members.png') }}" width="48" height="48" alt="Icon" />Members</a>
    </div>
    <div>
        <a href="{{ url('admin/teams') }}"><img src="{{ asset('icons/48_teams.png') }}" width="48" height="48" alt="Icon" />Teams</a>
    </div>
    <div>
        <a href="{{ url('admin/downloads') }}"><img src="{{ asset('icons/48_files.png') }}" width="48" height="48" alt="Icon" />Downloads</a>
    </div>
    <div>
        <a href="{{ url('admin/contact') }}"><img src="{{ asset('icons/48_contact.png') }}" width="48" height="48" alt="Icon" />Messages</a>
    </div>
    <div>
        <a href="{{ url('admin/visitors') }}"><img src="{{ asset('icons/48_visitors.png') }}" width="48" height="48" alt="Icon" />Visitors</a>
    </div>
</div>

<div class="widgets-row">
    @widget('Comments::LatestComments')

    @widget('Users::LatestUsers')
</div>

{{ $feed }}