<div class="widget-navigation-default widget-navigation">
    <ul class="list-unstyled">
        @foreach ($items as $item)
            <li class="item-level-{{ $item->level }}">
                {{ link_to($item->url,  $item->title) }}
            </li>
        @endforeach
    </ul>
</div>