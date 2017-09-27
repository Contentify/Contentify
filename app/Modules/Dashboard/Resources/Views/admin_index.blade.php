
<h2 class="section">{{ trans('app.quick_access') }}</h2>
<div class="quick-access clearfix">     
    <div>
        <a href="{!! url('admin/news') !!}">{!! HTML::fontIcon('newspaper-o') !!} {{ trans('app.object_news') }}</a>
    </div>
    <div>
        <a href="{!! url('admin/pages') !!}">{!! HTML::fontIcon('file') !!}  {{ trans('app.object_pages') }}</a>
    </div>
    <div>
        <a href="{!! url('admin/matches') !!}">{!! HTML::fontIcon('crosshairs') !!}  {{ trans('app.object_matches') }}</a>
    </div>
    <div>
        <a href="{!! url('admin/images') !!}">{!! HTML::fontIcon('image') !!}  {{ trans('app.object_images') }}</a>
    </div>
    <div>
        <a href="{!! url('admin/videos') !!}">{!! HTML::fontIcon('youtube-play') !!}  {{ trans('app.object_videos') }}</a>
    </div>
    <div>
        <a href="{!! url('admin/downloads') !!}">{!! HTML::fontIcon('folder') !!}  {{ trans('app.object_downloads') }}</a>
    </div>

    <!-- Second row  -->

    <div>
        <a href="{!! url('admin/users') !!}">{!! HTML::fontIcon('user') !!}  {{ trans('app.object_users') }}</a>
    </div>

    <div>
        <a href="{!! url('admin/members') !!}">{!! HTML::fontIcon('group') !!}  {{ trans('app.object_members') }}</a>
    </div>
    <div>
        <a href="{!! url('admin/teams') !!}">{!! HTML::fontIcon('flag') !!}  {{ trans('app.object_teams') }}</a>
    </div>
    <div>
        <a href="{!! url('admin/contact') !!}">{!! HTML::fontIcon('envelope') !!}  {{ trans('app.object_messages') }}</a>
    </div>
    <div>
        <a href="{!! url('admin/visitors') !!}">{!! HTML::fontIcon('pie-chart') !!}  {{ trans('app.object_visitors') }}</a>
    </div>
    <div>
        <a href="{!! url('admin/config') !!}">{!! HTML::fontIcon('cog') !!}  {{ trans('app.object_config') }}</a>
    </div>
</div>

@if (user()->hasAccess('users'))
    <h2 class="section">{{ trans('app.object_visitors') }}</h2>
    @widget('Visitors::Chart')
@endif

<div class="widgets-row">
    @widget('Users::LatestUsers')

    @widget('Comments::LatestComments')
</div>

{!! $feed !!}