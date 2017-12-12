<h1 class="page-title">{{ trans_object('servers') }}</h1>

<div class="servers clearfix">
    @foreach ($servers as $server)
        <div class="server">
            <table class="table horizontal">
                <tbody>
                    <tr>
                        <th>{!! trans('app.title') !!}</th>
                        <td>{{ $server->title }}</td>
                    </tr>
                    @if ($server->game)
                        <tr>
                            <th>{!! trans('app.object_game') !!}</th>
                            <td>
                                @if ($server->game->icon)
                                    {!! HTML::image($server->game->uploadPath().$server->game->icon, $server->game->title) !!}
                                @endif
                                {{ $server->game->title }}
                            </td>
                        </tr>
                    @endif
                    @if ($server->description)
                        <tr>
                            <th>{!! trans('app.description') !!}</th>
                            <td>{{ $server->description }}</td>
                        </tr>
                    @endif
                    <tr>
                        <th>{!! trans('servers::slots') !!}</th>
                        <td>{{ $server->slots }}</td>
                    </tr>
                    <tr>
                        <th>{!! trans('servers::hoster') !!}</th>
                        <td>{{ $server->hoster }}</td>
                    </tr>
                    <tr>
                        <th>{!! trans('app.ip') !!}</th>
                        <td>{{ $server->ip }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    @endforeach
</div>

{!! $servers->render() !!}