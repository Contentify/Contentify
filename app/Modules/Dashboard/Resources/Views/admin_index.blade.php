
<h2 class="section">{{ trans('app.quick_access') }}</h2>
<div class="quick-access clearfix">
    <a href="{!! url('admin/news') !!}"><span>{!! HTML::fontIcon('newspaper') !!}</span> {{ trans('app.object_news') }}</a>

    <a href="{!! url('admin/pages') !!}"><span>{!! HTML::fontIcon('file') !!}</span> {{ trans('app.object_pages') }}</a>

    <a href="{!! url('admin/matches') !!}"><span>{!! HTML::fontIcon('crosshairs') !!}</span> {{ trans('app.object_matches') }}</a>

    <a href="{!! url('admin/images') !!}"><span>{!! HTML::fontIcon('image') !!}</span> {{ trans('app.object_images') }}</a>

    <a href="{!! url('admin/videos') !!}"><span>{!! HTML::fontIcon('youtube') !!}</span> {{ trans('app.object_videos') }}</a>

    <a href="{!! url('admin/downloads') !!}"><span>{!! HTML::fontIcon('folder') !!}</span> {{ trans('app.object_downloads') }}</a>

    <!-- Second row  -->

    <a href="{!! url('admin/users') !!}"><span>{!! HTML::fontIcon('user') !!}</span> {{ trans('app.object_users') }}</a>

    <a href="{!! url('admin/members') !!}"><span>{!! HTML::fontIcon('users') !!}</span> {{ trans('app.object_members') }}</a>

    <a href="{!! url('admin/teams') !!}"><span>{!! HTML::fontIcon('flag') !!}</span> {{ trans('app.object_teams') }}</a>

    <a href="{!! url('admin/contact') !!}"><span>{!! HTML::fontIcon('envelope') !!}</span> {{ trans('app.object_messages') }}</a>

    <a href="{!! url('admin/visitors') !!}"><span>{!! HTML::fontIcon('chart-pie') !!}</span> {{ trans('app.object_visitors') }}</a>

    <a href="{!! url('admin/config') !!}"><span>{!! HTML::fontIcon('cog') !!}</span> {{ trans('app.object_config') }}</a>
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