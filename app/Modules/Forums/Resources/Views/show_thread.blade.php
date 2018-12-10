<h1 class="page-title">
    <a class="back" href="{!! url('forums/'.$forumThread->forum->id.'/'.$forumThread->forum->slug) !!}">{!! HTML::fontIcon('chevron-left') !!}</a>
    {{ $forumThread->title }}
</h1>

<div class="buttons">
@if (user())
    @if (user()->hasAccess('forums', PERM_UPDATE))
        <a class="btn btn-default" href="{!! url('forums/threads/sticky/'.$forumThread->id) !!}">
            {{ trans('forums::sticky') }}: {!! HTML::fontIcon($forumThread->sticky ? 'check' : 'times') !!}
        </a>
        <a class="btn btn-default" href="{!! url('forums/threads/closed/'.$forumThread->id) !!}">
            {{ trans('forums::closed') }}: {!! HTML::fontIcon($forumThread->closed ? 'check' : 'times') !!}
            </a>
        <a class="btn btn-default" href="{!! url('forums/threads/move/'.$forumThread->id) !!}">{!!  trans('forums::move') !!}</a>
    @endif
    @if (user()->hasAccess('forums', PERM_DELETE))
        <a class="btn btn-default" href="{!! url('forums/threads/delete/'.$forumThread->id) !!}">{!! trans('app.delete') !!}</a>
    @endif
@endif
</div>

{!! $forumPosts->render() !!}

<div class="forumPosts">
    <?php $forumPostNumber = $forumPosts->firstItem() ?>
    @foreach($forumPosts as $forumPost)
        @include('forums::show_post', compact('forumPost', 'forumPostNumber'))
        <?php $forumPostNumber++ ?>
    @endforeach
</div>

@if ($forumPostNumber - $forumPosts->firstItem() > 2)
    {!! $forumPosts->render() !!}
@endif

@if ($forumThread->closed)
    {!! trans('forums::closed_info') !!}
@else
    @if (user())
        @include('forums::post_form', compact('forumThread'))
    @endif
@endif

<script>
    $(document).ready(function()
    {
        if (window.location.hash) {
            var hash = window.location.hash; // Note: Starts with #
            var $el = $(hash);

            if ($el.length > 0) {
                $el.addClass('highlight');
            }
        }

        $('.page .post .quote').click(function(event)
        {
            event.preventDefault();

            var $self       = $(this);
            var $post       = $self.closest('.post');
            var $textarea   = $('.page form textarea');
            var id          = $post.attr('data-id');
            var creator     = $post.find('.creator-name').text();

            if (creator) {
                creator = '=' + creator;
            }

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
