<div class="widget widget-calendar">
    <div class="buttons">
        {{ $month }} / {{ $year }}
        <?php
            $prevYear = $year;
            $prevMonth = $month - 1;

            if ($prevMonth == 0) {
                $prevYear = $year - 1;
                $prevMonth = 12;
            }

            $nextYear = $year;
            $nextMonth = $month + 1;

            if ($nextMonth == 13) {
                $nextYear = $year + 1;
                $nextMonth = 1;
            }
        ?>
        <a class="prev" href="{{ url('calendar/'.$prevYear.'/'.$prevMonth) }}">&lt;</a>
        <a class="next" href="{{ url('calendar/'.$nextYear.'/'.$nextMonth) }}">&gt;</a>
    </div>

    <div class="day-names">
        <div>{{ trans('app.monday') }}</div>
        <div>{{ trans('app.tuesday') }}</div>
        <div>{{ trans('app.wednesday') }}</div>
        <div>{{ trans('app.thursday') }}</div>
        <div>{{ trans('app.friday') }}</div>
        <div>{{ trans('app.saturday') }}</div>
        <div>{{ trans('app.sunday') }}</div>
    </div>

    <div class="days">
        @while ($day <= $lastOfMonth or ($day->dayOfWeek != Carbon::MONDAY))
            @if ($day < $firstOfMonth)
                <div class="day before-month">{{ $day->day }}</div>
            @else
                @if ($day <= $lastOfMonth)
                    <?php 
                        $hasEvents  = '';
                        $today      = '';
                        $content    = '';

                        if ($day->isToday()) {
                            $today = 'today';
                        }
                    ?>
                    @while (sizeof($events) > 0 and $day->isSameDay($events->last()->starts_at))
                        <?php 
                            $hasEvents  = 'has-events';
                            $event      = $events->pop();
                            $content .= '<a href="'.url('events/'.$event->id.'/'.$event->slug).'" title="'.e($event->title).'" target="blank">'.e($event->title).'</a>';
                        ?>
                    @endwhile

                    <div class="day in-month {{ $hasEvents }} {{ $today }}">
                        <span class="day-number">{{ $day->day }}</span>
                        {!! $content !!}
                    </div>
                @else
                    <div class="day after-month">{{ $day->day }}</div>
                @endif
            @endif

            <?php $day->addDay() ?>
        @endwhile
    </div>
</div>

<script>
    $(document).ready(function() 
    {
        $('.widget-calendar .buttons .prev, .widget-calendar .buttons .next').click(function(event)
        {
            event.preventDefault();

            var $widget = $(this).parent().parent();
            
            $widget.load($(this).attr('href'));
        });
        $('.widget-calendar .day.before-month').click(function()
        {
            $('.widget-calendar .buttons .prev').click();
        });
        $('.widget-calendar .day.after-month').click(function()
        {
            $('.widget-calendar .buttons .next').click();
        });
    });
</script>