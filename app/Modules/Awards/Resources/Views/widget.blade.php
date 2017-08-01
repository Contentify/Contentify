<div class="widget widget-awards">
    <table class="table" data-not-respsonsive="1">
        <tbody>
        @foreach ($awards as $award)
            <tr>
                <td>
                    {!! $award->positionIcon() !!}
                </td>
                <td>
                    @if ($award->url)
                        <a href="{{ $award->url }}" target="_blank" title="{{ $award->title }}">
                            {{ $award->title }}
                        </a>
                    @else
                        {{ $award->title }}
                    @endif
                </td>
                <td>
                    {{ $award->tournament ? $award->tournament->short : null }}
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>