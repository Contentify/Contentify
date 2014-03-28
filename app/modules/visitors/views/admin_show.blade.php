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
            grid:   { borderWidth: 2, borderColor: '#BBB', hoverable: true, clickable: true }
        }
    );

    $("#chart").bind("plothover", function (event, pos, item) {
        if (item) {
            $('.days tbody tr').each(function()
            {
                if ($(this).attr('data-day') == item.datapoint[0]) {
                    if (! $(this).hasClass('hover')) $(this).addClass('hover');
                } else {
                    if ($(this).hasClass('hover')) $(this).removeClass('hover');
                }
            });
        }
    });
</script>

<table class="days content-table">
    <thead>
        <tr>
            <th>{{ trans('app.date') }}</th>
            <th>{{ trans('visitors::visitors') }}</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($days as $day)
        <tr data-day="{{ $day->day }}">
            <td>{{ $day->date }}</td>
            <td>{{ $day->visitors }}</td>
        </tr>
        @endforeach
    </tbod>
</table>