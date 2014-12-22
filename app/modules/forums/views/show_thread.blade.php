<h1 class="page-title">
    <a class="back" href="{{ url('forums/'.$forumThread->forum->id.'/'.$forumThread->forum->slug) }}">&lt;</a>
    {{{ $forumThread->title }}}
</h1>

<div class="buttons">
@if (user())
    @if (user()->hasAccess('forums', PERM_UPDATE))
        <a href="{{ url('forums/threads/sticky/'.$forumThread->id) }}">{{ trans('forums::sticky') }}</a>
        <a href="{{ url('forums/threads/closed/'.$forumThread->id) }}">{{ trans('forums::closed') }}</a>
        <a href="{{ url('forums/threads/move/'.$forumThread->id) }}">{{  trans('forums::move') }}</a>
    @endif
    @if (user()->hasAccess('forums', PERM_DELETE))
        <a href="{{ url('forums/threads/delete/'.$forumThread->id) }}">{{ trans('app.delete') }}</a>
    @endif
@endif
</div>

{{ $forumPosts->links() }}

<div class="forumPosts">
    <?php $forumPostNumber = $forumPosts->getFrom() ?>
    @foreach($forumPosts as $forumPost)
        @include('forums::show_post', compact('forumPost', 'forumPostNumber'))
        <?php $forumPostNumber++ ?>
    @endforeach
</div>

@if ($forumThread->closed)
    {{ trans('forums::closed_info') }}
@else
    @if (user())
        @include('forums::post_form', compact('forumThread'))
    @endif
@endif

{{ $forumPosts->links() }}