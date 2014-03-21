<table class="content-table">
    <tr>
        <th>Setting</th>
        <th>Value</th>
    </tr>
    @foreach ($settings as $key => $value)
    <tr>
        <td>{{ $key }}</td>
        <td>{{ $value }}</td>
    </tr>
    @endforeach
</table>