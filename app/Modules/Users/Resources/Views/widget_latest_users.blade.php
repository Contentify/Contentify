<div class="widget widget-latest-users">
    <table class="table">
        <thead>
            <tr>
                <th>{!! trans('users::latest_users') !!}</th>
                <th>{!! trans('app.date') !!}</th>
            </tr>
        </thead>
        
        <tbody>
            @foreach ($users as $user)
                <tr>
                    <td>{!! link_to('users/'.$user->id.'/'.$user->slug, $user->username) !!}</td>
                    <td>{{ $user->created_at }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>