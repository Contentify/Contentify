<html lang="en">
<head>
    <title>{{ trans('auth::new_pw') }}</title>
    <meta charset="utf-8">
</head>
<body>
    <p>{{ trans('auth::pw_generated', [Config::get('app.name')]) }}</p>

    <table>
        <tr>
            <td><strong>{{ trans('app.email') }}:</strong></td>
            <td>{{ $user->email }}</td>
        </tr>
        <tr>
            <td><strong>{{ trans('app.password') }}:</strong></td>
            <td>{{ $password }}</td>
        </tr>
    </table>
</body>
</html>