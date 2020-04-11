<div class="widget widget-online">
    <table class="table">
        <tbody>
        @section('users-widget-online-index')
            @foreach ($users as $user)
                <tr>
                    <td>{!! link_to('users/'.$user->id.'/'.$user->slug, $user->username) !!}</td>
                    <td>{{ $user->created_at }}</td>
                </tr>
            @endforeach
        @show
        </tbody>
    </table>
</div>
