<html lang="en">
<head>
    <title>New Password</title>
    <meta charset="utf-8">
</head>
<body>
    <p>A new password has been generated for {{ Config::get('app.title') }}.</p>

    <table>
        <tr>
            <td><strong>Email:</strong></td>
            <td>{{ $user->email }}</td>
        </tr>
        <tr>
            <td><strong>Password:</strong></td>
            <td>{{ $password }}</td>
        </tr>
    </table>
</body>
</html>