<article class="partner">
    <header>
        <h2 id="partner-section-{{ $partner->slug }}">{{ $partner->title }}</h2>
    </header>
    <div class="content">
        @section('partners-partner-image')
            @if ($partner->image)
                <div class="image">
                    <img class="img-responsive" src="{!! $partner->uploadPath().$partner->image !!}" alt="{{ $partner->title }}">
                </div>
            @endif
        @show

        <p>
        @section('partners-partner-text')
            {!! $partner->text !!}
        @show
        </p>

        @if ($partner->url)
            <a class="btn btn-default" href="{{ url('partners/url/'.$partner->id) }}" title="{{ trans('app.website') }}" target="_blank">{!! HTML::fontIcon('link') !!} {{ $partner->url }}</a>
        @endif

        @section('partners-partner-socials')
            @if ($partner->facebook)
                <?php $url = filter_var($partner->facebook, FILTER_VALIDATE_URL) ? $partner->facebook : 'https://www.facebook.com/'.$partner->facebook ?>
                <a class="btn btn-default" href="{{ $url }}" target="_blank" title="Facebook">{!! HTML::fontIcon('facebook') !!}</a>
            @endif

            @if ($partner->twitter)
                <?php $url = filter_var($partner->twitter, FILTER_VALIDATE_URL) ? $partner->twitter : 'https://www.twitter.com/'.$partner->twitter ?>
                <a class="btn btn-default" href="{{ $url }}" target="_blank" title="Twitter">{!! HTML::fontIcon('twitter') !!}</a>
            @endif

            @if ($partner->youtube)
                <?php $url = filter_var($partner->youtube, FILTER_VALIDATE_URL) ? $partner->youtube : 'https://www.youtube.com/channel/'.$partner->youtube ?>
                <a class="btn btn-default" href="{{ $url }}" target="_blank" title="YouTube">{!! HTML::fontIcon('youtube') !!}</a>
            @endif

            @if ($partner->discord)
                <?php $url = filter_var($partner->discord, FILTER_VALIDATE_URL) ? $partner->discord : 'https://www.discord.gg/'.$partner->discord ?>
                <a class="btn btn-default" href="{{ $url }}" target="_blank" title="Discord">{!! HTML::fontIcon('discord') !!}</a>
            @endif
        @show
    </div>
</article>
