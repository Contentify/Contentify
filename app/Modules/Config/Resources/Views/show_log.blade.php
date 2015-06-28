{!! button(trans('app.delete'), url('admin/config/log/clear'), 'trash') !!}

<pre style="overflow: scroll; margin-top: 40px;">
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