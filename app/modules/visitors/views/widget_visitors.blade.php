<div class="widget-visitors">
    <ul class="layout-v">
        <li>
            <span>{{ trans('visitors::today') }}:</span> {{ $today }}
        </li>
        <li>
            <span>{{ trans('visitors::yesterday') }}:</span> {{ $yesterday }}
        </li>
        <li>
            <span>{{ trans('visitors::month') }}:</span> {{ $month }}
        </li>
        <li>
            <span>{{ trans('visitors::total') }}:</span> {{ $total }}
        </li>
    </ul>
</div>