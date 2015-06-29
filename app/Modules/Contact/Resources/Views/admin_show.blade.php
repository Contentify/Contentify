<table class="table" data-not-responsive="1">
    <tbody>
        <tr>
            <td>
                {!! trans('app.creator') !!}
            </td>
            <td>
                {{ $msg->username }}
            </td>
        </tr>
        <tr>
            <td>
                {!! trans('app.created_at') !!}
            </td>
            <td>
                {!! $msg->created_at !!}
            </td>
        </tr>
        <tr>
            <td>
                {!! trans('app.email') !!}
            </td>
            <td>
                {{ $msg->email }}
            </td>
        </tr>
        <tr>
            <td>
                {!! trans('app.ip') !!}
            </td>
            <td>
                {!! $msg->ip !!}
            </td>
        </tr>
    </tbody>
</table>

<hr>

<table class="table" data-not-responsive="1">
    <tbody>
        <tr>
            <td>
                {!! trans('app.subject') !!}
            </td>
            <td>
                {{ $msg->title }}
            </td>
        </tr>
        <tr>
            <td>
                {!! trans('app.message') !!}
            </td>
            <td>
                {!! $msg->text !!}
            </td>
        </tr>
    </tbody>
</table>

{!! Form::button(trans('app.reply'), ['onclick' => "location.href='mailto:{$msg->email}'"]) !!}