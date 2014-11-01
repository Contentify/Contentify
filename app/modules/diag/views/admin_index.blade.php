<table class="table">
    <tr>
        <th>{{ trans('diag::setting') }}</th>
        <th>{{ trans('diag::value') }}</th>
    </tr>
    @foreach ($settings as $key => $value)
    <tr>
        <td>{{ $key }}</td>
        <td>{{ $value }}</td>
    </tr>
    @endforeach
</table>