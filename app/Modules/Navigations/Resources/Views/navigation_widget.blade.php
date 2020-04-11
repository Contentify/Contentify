<div class="widget-navigation-default widget-navigation">
    <ul class="list-unstyled">
    @section('navigations-widget-index')
        @foreach ($items as $item)
            <li class="item-level-{!! $item->level !!}">
                {!! link_to($item->url, $translate ? trans($item->title) : $item->title) !!}
            </li>
        @endforeach
    @show
    </ul>
</div>
