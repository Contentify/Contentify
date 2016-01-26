<html lang="en">
<head>
    <title>{{ trans('contact::mail_title') }}</title>
    <meta charset="utf-8">
</head>
<body>
    <p><strong>{{ $msg->username }}</strong> {{ trans('contact::mail_created') }}</p>

    <p>{{ trans('app.subject') }}: <em>{{ $msg->title }}</em></p>
    
    <p>{!! link_to('admin/contact/'.$msg->id, trans('contact::mail_link')) !!}</p>
</body>
</html>