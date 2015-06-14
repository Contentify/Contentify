<h1 class="page-title">
    <a class="back" href="{{ url('forums/'.$forumThread->forum->id.'/'.$forumThread->forum->slug) }}">{{ HTML::fontIcon('chevron-left') }}</a>
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

{{ $forumPosts->render() }}

<div class="forumPosts">
    <?php $forumPostNumber = $forumPosts->firstItem() ?>
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

{{ $forumPosts->render() }}

<script>
    $(document).ready(function()
    {
        $('.page .post .quote').click(function(event)
        {
            event.preventDefault();

            var $self       = $(this);
            var $textarea   = $('.page form textarea');
            var id          = $self.attr('data-id');
            var creator     = $self.find('.creator-name').text();

            if (creator) creator = '=' + creator;

            $.ajax({
                url: contentify.baseUrl + 'forums/posts/' + id,
                type: 'GET'
            }).success(function(post)
            {
                $textarea.val($textarea.val() + '[quote' + creator + ']' + post.text + '[/quote]\n').focus();
            }).fail(function(response)
            {
                contentify.alertRequestFailed(response);
            });
        });
    });
</script>