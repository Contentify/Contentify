<html lang="en">
<head>
    <title>New Contact Message</title>
    <meta charset="utf-8">
</head>
<body>
    <p><strong>{{ $msg->username }}</strong> created a new contact message.</p>

    <p>Subject: <em>{{ $msg->title }}</em></p>
    
    <p>{!! link_to('admin/contact/'.$msg->id, 'Click here to view the message.') !!}</p>
</body>
</html>