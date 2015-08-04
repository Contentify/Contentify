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
        <div>Monday</div>
        <div>Tuesday</div>
        <div>Wednesday</div>
        <div>Thursday</div>
        <div>Friday</div>
        <div>Saturday</div>
        <div>Sunday</div>
    </div>

    <div class="days">
        @while ($day <= $lastOfMonth or ($day->dayOfWeek != Carbon::MONDAY))
            @if ($day < $firstOfMonth)
                <div class="day before-month">{{ $day->day }}</div>
            @else
                @if ($day <= $lastOfMonth)
                    <?php 
                        $class = '';
                        $content = '';
                    ?>
                    @while (sizeof($events) > 0 and $day->isSameDay($events->last()->starts_at))
                        <?php 
                            $class = 'has-events';
                            $event = $events->pop();
                            $content .= '<a href="'.url('events/'.$event->id.'/'.$event->slug).'" title="'.e($event->title).'" target="blank">'.e($event->title).'</a>';
                        ?>
                    @endwhile

                    <div class="day in-month {{ $class }}">
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
        $('.widget .buttons .prev, .widget .buttons .next').click(function(event)
        {
            event.preventDefault();

            var $widget = $(this).parent().parent();
            
            $widget.load($(this).attr('href'));
        });
    });
</script>