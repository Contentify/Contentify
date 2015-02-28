<table class="table">
    <thead>
        <tr>
            <th>{{ trans('diag::setting') }}</th>
            <th>{{ trans('diag::value') }}</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($settings as $key => $value)
            <tr>
                <td>{{ $key }}</td>
                <td>{{ $value }}</td>
            </tr>
        @endforeach
    </tbody>
</table>