{!! button(trans('app.delete'), url('admin/config/log/clear'), 'trash') !!}

{!! button(trans('app.download'), url('admin/config/plain-log'), 'download') !!}

<p class="pull-right form-control-static">{{ trans('app.size') }}: {{ $size }} Bytes</p>

<pre class="space-top-huge">
    {!! $content !!}
</pre>

<script>
    $(document).ready(function()
    {
        $('.page .item').click(function()
        {
            $(this).find('.stack').toggle();
        });
    });
</script>