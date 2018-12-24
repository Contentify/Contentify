<div class="items">
    @forelse ($streamItems as $item)
        @include('news::stream_item', compact('item', 'more'))
    @empty
        <p>{{ trans('app.nothing_here') }}</p>
    @endforelse
</div>

<div class="clearfix"></div>

<div class="loading hidden text-center">{!! HTML::fontIcon('spinner', null, 'fa-spin') !!} {{ trans('app.loading') }}…</div>

<script>
    $(document).ready(function()
    {
        var timestamp = 0;
        var loading = false;
        var lastScrollTop = 0;

        $(document).scroll(function() 
        {
            var scrollTop = $(this).scrollTop();

            if (scrollTop < lastScrollTop || ! {{ $more }}) {
                return;
            }
            lastScrollTop = scrollTop;

            var scrollBottom = scrollTop + $(window).height();

            var $lastItem = $('.page .item').last();
            if ($lastItem.length > 0) {
                var lastItemBottom = $lastItem.offset().top + $lastItem.height();

                if (scrollBottom > lastItemBottom) {
                    timestamp = parseInt($lastItem.attr('data-timestamp')) - 1;

                    if (! loading) {
                        loading = true;
                        $('.page .loading').removeClass('hidden');

                        $.get(contentify.baseUrl + 'news/showStream/' + timestamp, function(data)
                        {
                            var $items = $(data).addClass('new');
                            $('.page .items').append($items).append('<div class="bugfix"></div>');
                            $('.page .items .bugfix').last().hide().show(0); // Force browsers to show CSS transitions
                            $items.removeClass('new');
                        }).always(function()
                        {
                            if ($('.page .items .item').last().attr('data-more') == 1) {
                                loading = false;
                            }
                            $('.page .loading').addClass('hidden');
                        });
                    }
                }
            }

        });
    });
</script>
