<div class="widget widget-servers">
    <ul class="list-unstyled">
        @foreach ($servers as $server)
            <li>
                {{ $server->title }}: {{ $server->ip }}
            </li>
        @endforeach
    </ul>
</div>