<div class="sharing">
    <span>{{ trans('app.share_this') }}: </span>
    <a class="share-fb" href="http://www.facebook.com/sharer.php?u={!! urlencode(Request::url()) !!}" target="_blank">{!! HTML::fontIcon('facebook') !!}</a>
    <a class="share-tw" href="http://twitter.com/home?status={{ urlencode($news->title) }}%20{!! urlencode(Request::url()) !!}" target="_blank">{!! HTML::fontIcon('twitter') !!}</a>
</div>