<div id="chart"></div>

{{ HTML::script('libs/flot/flot.js') }}

<script>
    $.plot($("#chart"), [ [{{ $chart->dataSet }}] ],
        {
            lines:  { show: true },
            points: { show: true },
            xaxis:  {min: 1, max: {{ $chart->maxDay }}, tickSize: 1, tickDecimals: 0},
            yaxis:  {min: 0, tickSize: 1, tickDecimals: 0},
            colors: ["#FF6600"],
            grid:   { borderWidth: 2, borderColor: '#BBB', hoverable: true, clickable: true }
        }
    );
</script>