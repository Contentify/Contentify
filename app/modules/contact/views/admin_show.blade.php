<table class="table">
    <tbody>
        <tr>
            <td>
                {{ trans('app.creator') }}
            </td>
            <td>
                {{{ $msg->username }}}
            </td>
        </tr>
        <tr>
            <td>
                {{ trans('app.created_at') }}
            </td>
            <td>
                {{ $msg->created_at }}
            </td>
        </tr>
        <tr>
            <td>
                {{ trans('app.email') }}
            </td>
            <td>
                {{{ $msg->email }}}
            </td>
        </tr>
        <tr>
            <td>
                {{ trans('contact::ip') }}
            </td>
            <td>
                {{ $msg->ip }}
            </td>
        </tr>
    </tbody>
</table>

<hr />

<table class="table">
    <tbody>
        <tr>
            <td>
                {{ trans('contact::subject') }}
            </td>
            <td>
                {{{ $msg->title }}}
            </td>
        </tr>
        <tr>
            <td>
                {{ trans('contact::message') }}
            </td>
            <td>
                {{ $msg->text }}
            </td>
        </tr>
    </tbody>
</table>

{{ Form::button(trans('contact::reply'), ['onclick' => "location.href='mailto:{$msg->email}'"]) }}