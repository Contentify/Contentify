<div id="chart"></div>

{{ HTML::script('libs/flot/flot.js') }}

<script>
    $.plot($("#chart"), [ [{{ $dataSet }}] ],
        {
            lines:  { show: true },
            points: { show: true },
            xaxis:  {min: 1, max: {{ $maxDay }}, tickSize: 1},
            yaxis:  {min: 0},
            colors: ["#FF6600"],
            grid: { borderWidth: 2, borderColor: '#BBB' }
        }
    );
</script>

<table class="content-table">
    <thead>
        <tr>
            <th>{{ trans('app.date') }}</th>
            <th>{{ trans('visitors::visitors') }}</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($days as $day)
        <tr>
            <td>{{ $day->date }}</td>
            <td>{{ $day->visitors }}</td>
        </tr>
        @endforeach
    </tbod>
</table>