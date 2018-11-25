@foreach ($settings as $settingsGroup)
    <table class="table">
        <thead>
            <tr>
                <th>{{ trans('app.setting') }}</th>
                <th>{{ trans('app.value') }}</th>
            </tr>
        </thead>
        <tbody>
            @if (! is_array($settingsGroup))
                dd($settingsGroup);
            @endif
            @foreach ($settingsGroup as $key => $value)
                <tr>
                    <td>
                        {!! $key !!}
                    </td>
                    <td>
                        {!! $value !!}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endforeach