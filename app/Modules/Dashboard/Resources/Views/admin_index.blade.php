
<h2 class="section">{{ trans('app.quick_access') }}</h2>
<div class="quick-access clearfix">     
    <div>
        <a href="{!! url('admin/news') !!}">{!! HTML::fontIcon('newspaper-o') !!} News</a>
    </div>
    <div>
        <a href="{!! url('admin/pages') !!}">{!! HTML::fontIcon('file') !!} Pages</a>
    </div>
    <div>
        <a href="{!! url('admin/matches') !!}">{!! HTML::fontIcon('crosshairs') !!} Matches</a>
    </div>
    <div>
        <a href="{!! url('admin/images') !!}">{!! HTML::fontIcon('image') !!} Images</a>
    </div>
    <div>
        <a href="{!! url('admin/videos') !!}">{!! HTML::fontIcon('youtube-play') !!} Videos</a>
    </div>
    <div>
        <a href="{!! url('admin/downloads') !!}">{!! HTML::fontIcon('folder') !!} Downloads</a>
    </div>

    <!-- Second row  -->

    <div>
        <a href="{!! url('admin/users') !!}">{!! HTML::fontIcon('user') !!} Users</a>
    </div>

    <div>
        <a href="{!! url('admin/members') !!}">{!! HTML::fontIcon('group') !!} Members</a>
    </div>
    <div>
        <a href="{!! url('admin/teams') !!}">{!! HTML::fontIcon('flag') !!} Teams</a>
    </div>
    <div>
        <a href="{!! url('admin/contact') !!}">{!! HTML::fontIcon('envelope') !!} Messages</a>
    </div>
    <div>
        <a href="{!! url('admin/visitors') !!}">{!! HTML::fontIcon('pie-chart') !!} Visitors</a>
    </div>
    <div>
        <a href="{!! url('admin/config') !!}">{!! HTML::fontIcon('cog') !!} Config</a>
    </div>
</div>

@if (user()->hasAccess('users'))
    <h2 class="section">Visitors</h2>
    @widget('Visitors::Chart')
@endif

<div class="widgets-row">
    @widget('Comments::LatestComments')

    @widget('Users::LatestUsers')
</div>

{!! $feed !!}