<div id="chart"></div>

{!! HTML::script('vendor/flot/flot.js') !!}

<script>
    $.plot($("#chart"), [ [{!! $chart->dataSet !!}] ],
        {
            lines:      { show: true, lineWidth: 3 },
            shadowSize: 0,
            points:     { show: true },
            xaxis:      {min: 1, max: {!! $chart->maxDay !!}, tickSize: 1, tickDecimals: 0},
            yaxis:      {min: 0, tickDecimals: 0},
            colors:     ["#69DD95"],
            grid:       { borderWidth: 2, borderColor: '#BBB', hoverable: true, clickable: true }
        }
    );
</script>