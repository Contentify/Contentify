<html lang="en">
<head>
    <title>Password Reset</title>
    <meta charset="utf-8">
</head>
<body>
    <p>Please open the following link to generate a new password for {{ Config::get('app.title') }}.</p>

    <table>
        <tr>
            <td><strong>Email:</strong></td>
            <td>{{ $user->email }}</td>
        </tr>
        <tr>
            <td><strong>Link:</strong></td>
            <td>{{ link_to('auth/restore/new/'.$user->email.'/'.$user->reset_password_code) }}</td>
        </tr>
    </table>
    
    <p>If you don't want to generate a new password ignore this mail.</p>
</body>
</html>