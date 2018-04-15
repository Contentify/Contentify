
<?php
    $socialLinks = [
        'Facebook' => 'https://www.facebook.com/',
        'Twitter' => 'https://twitter.com/',
        'Twitch' => 'https://www.twitch.tv/',
        'YouTube' => 'https://www.youtube.com/channel/',
        'Instagram' => 'https://www.instagram.com/',
        'Discord' => 'https://www.discord.gg/',
    ]
?>
@foreach($socialLinks as $title => $url)
    @if (Config::get('app.facebook'))
        @if (isset($wrapperTag))
            {!! '<'.$wrapperTag.'>' !!}
        @endif
        <?php $name = strtolower($title) ?>
        <a class="social-link" href="{{ $url }}{{ Config::get('app.'.$name) }}" target="_blank" title="{{ $title }}">{!! HTML::fontIcon($name) !!}</a>
        @if (isset($wrapperTag))
            {!! '</'.$wrapperTag.'>' !!}
        @endif
    @endif
@endforeach